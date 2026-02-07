<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categoryId = request('categoria');
        $searchTerm = trim((string) request('busca'));
        $categories = Category::orderBy('nome')->get();
        
        $products = Product::with('category')
            ->when($categoryId, fn ($query) => $query->where('categoria_id', $categoryId))
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $likeTerm = '%' . $searchTerm . '%';
                $query->where(function ($subQuery) use ($likeTerm) {
                    $subQuery->where('nome', 'like', $likeTerm)
                        ->orWhere('descricao', 'like', $likeTerm);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $fallbacks = [
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80',
        ];

        $products->setCollection(
            $products->getCollection()->values()->map(function ($product, $index) use ($fallbacks) {
                $product->display_photo = $this->getDisplayPhoto($product, $fallbacks, $index);
                return $product;
            })
        );

        $user = auth()->user();
        $isAuthenticated = (bool) $user;
        $authUserName = $user?->nome ?? $user?->name ?? 'Usuario';
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('products.index', compact(
            'products',
            'categories',
            'categoryId',
            'searchTerm',
            'isAuthenticated',
            'authUserName',
            'cartCount'
        ));
    }

    public function show(Product $product)
    {
        $product->load('category', 'user');
        $displayPhoto = $this->getDisplayPhoto($product);

        $user = auth()->user();
        $isAuthenticated = (bool) $user;
        $authUserName = $user?->nome ?? $user?->name ?? 'Usuario';
        $isAdmin = (bool) ($user?->is_admin ?? false);
        
        $canPurchase = $isAuthenticated && !$isAdmin && $product->user_id !== $user->id && (int) $product->quantidade > 0;
        
        $showAdminNote = $isAuthenticated && $isAdmin;
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('products.show', compact(
            'product',
            'displayPhoto',
            'isAuthenticated',
            'authUserName',
            'canPurchase',
            'showAdminNote',
            'cartCount'
        ));
    }

    public function myProducts()
    {
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('products.my', compact('products'));
    }

    public function adminIndex()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $products = Product::with(['category', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        if (auth()->user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Administradores não podem criar produtos.');
        }

        $categories = Category::orderBy('nome')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->is_admin) {
            abort(403, 'Administradores não podem criar produtos.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'foto' => 'required|image|max:2048', // 2MB max
        ]);

        $path = $request->file('foto')->store('products', 'public');

        auth()->user()->products()->create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'preco' => $validated['preco'],
            'quantidade' => $validated['quantidade'],
            'categoria_id' => $validated['categoria_id'],
            'foto' => 'storage/' . $path,
        ]);

        return redirect()->route('products.my')->with('success', 'Produto criado com sucesso!');
    }

    
    public function edit(Product $product)
    {
        $user = auth()->user();

        if ($product->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $categories = Category::orderBy('nome')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $user = auth()->user();

        if ($product->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = [
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'preco' => $validated['preco'],
            'quantidade' => $validated['quantidade'],
            'categoria_id' => $validated['categoria_id'],
        ];

        if ($request->hasFile('foto')) {
            if ($product->foto && file_exists(public_path($product->foto))) {
                @unlink(public_path($product->foto));
            }

            $path = $request->file('foto')->store('products', 'public');
            $data['foto'] = 'storage/' . $path;
        }

        $product->update($data);

        $redirectRoute = $user->is_admin ? 'admin.products.index' : 'products.my';
        return redirect()->route($redirectRoute)->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();

        if ($product->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $product->delete();

        $redirectRoute = $user->is_admin ? 'admin.products.index' : 'products.my';
        return redirect()->route($redirectRoute)->with('success', 'Produto excluído com sucesso!');
    }

    private function getDisplayPhoto($product, $fallbacks = [], $index = 0)
    {
        if ($product->foto) {
            return str_starts_with($product->foto, 'http') ? $product->foto : asset($product->foto);
        }
        if (!empty($fallbacks)) {
            return $fallbacks[$index % count($fallbacks)];
        }
        return 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80';
    }
}