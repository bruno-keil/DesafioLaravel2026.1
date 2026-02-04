<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->whereNotNull('foto')->latest()->take(1);
        }])->orderBy('nome')->get();

        $products = Product::with('category')
            ->when(auth()->check(), fn ($query) => $query->where('user_id', '!=', auth()->id()))
            ->latest()
            ->take(8)
            ->get();

        $heroImages = [
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1400&q=80',
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=1400&q=80',
        ];

        $categoryImages = [
            'mobile' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1400&q=80',
            'audio' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1400&q=80',
            'computadores' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80',
            'acessorios' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1400&q=80',
            'consoles' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1400&q=80',
            'smart home' => 'https://images.unsplash.com/photo-1512446733611-9099a758e6ce?auto=format&fit=crop&w=1400&q=80',
            'wearables' => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?auto=format&fit=crop&w=1400&q=80',
            'cameras' => 'https://images.unsplash.com/photo-1519183071298-a2962be96c2e?auto=format&fit=crop&w=1400&q=80',
        ];

        $categorySlides = $categories->values()->map(function ($category, $index) use ($heroImages, $categoryImages) {
            $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $categoryKey = strtolower(trim($category->nome));
            $image = null;
            $categoryProduct = $category->products->first();
            if ($categoryProduct?->foto) {
                $image = str_starts_with($categoryProduct->foto, 'http')
                    ? $categoryProduct->foto
                    : asset($categoryProduct->foto);
            }
            $image = $image ?? ($categoryImages[$categoryKey] ?? $heroImages[$index % count($heroImages)]);

            return [
                'title' => strtoupper($category->nome),
                'subtitle' => 'Veja anuncios da categoria ' . $category->nome . ' feitos por outros usuarios no LootBay.',
                'tag' => 'CATEGORIA ' . $number,
                'image' => $image,
                'category_id' => $category->id,
                'link' => route('products.index', ['categoria' => $category->id]),
            ];
        })->all();

        $slides = count($categorySlides) ? $categorySlides : [
            [
                'title' => 'MOBILE & WEARABLES',
                'subtitle' => 'Smartphones, relogios e devices da comunidade.',
                'tag' => 'CATEGORIA 01',
                'image' => $heroImages[0],
                'category_id' => null,
                'link' => route('products.index'),
            ],
            [
                'title' => 'AUDIO PREMIUM',
                'subtitle' => 'Headsets, caixas e setups sonoros anunciados por outros usuarios.',
                'tag' => 'CATEGORIA 02',
                'image' => $heroImages[1],
                'category_id' => null,
                'link' => route('products.index'),
            ],
            [
                'title' => 'CONSOLES & GAMES',
                'subtitle' => 'Jogos, controles e itens colecionaveis para gamers.',
                'tag' => 'CATEGORIA 03',
                'image' => $heroImages[2],
                'category_id' => null,
                'link' => route('products.index'),
            ],
            [
                'title' => 'SMART HOME',
                'subtitle' => 'Automacao e gadgets para deixar sua casa conectada.',
                'tag' => 'CATEGORIA 04',
                'image' => $heroImages[3],
                'category_id' => null,
                'link' => route('products.index'),
            ],
        ];

        $firstSlide = $slides[0];

        $fallbacks = [
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80',
        ];

        $products = $products->values()->map(function ($product, $index) use ($fallbacks) {
            $photo = null;
            if ($product->foto) {
                $photo = str_starts_with($product->foto, 'http') ? $product->foto : asset($product->foto);
            }
            $product->display_photo = $photo ?? $fallbacks[$index % count($fallbacks)];

            return $product;
        });

        $user = auth()->user();
        $isAuthenticated = (bool) $user;
        $authUserName = $user?->nome ?? $user?->name ?? 'Usuario';
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('welcome', compact(
            'products',
            'slides',
            'firstSlide',
            'isAuthenticated',
            'authUserName',
            'cartCount'
        ));
    }
}
