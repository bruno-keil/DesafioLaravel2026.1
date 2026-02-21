<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PagSeguroController extends Controller
{
    public function createCheckout(Request $request)
    {
        $url = config('services.pagseguro.checkout_url');
        $token = config('services.pagseguro.token');

        $items = json_decode($request->input('items'), true);

        $format_items = array_map(fn($item): array => [
                'name' =>  $item['name'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item['price'] * 100
            ], array: $items);

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . $token,
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
          ->post($url, [
              'items' => $format_items,
              'reference_id' => uniqid(prefix: 'ORDER_')
          ]);

        if ($response->successful()) {
            DB::transaction(function () use ($items, $request) {
                $transaction = Transaction::create([
                    'comprador_id' => $request->user()->id,
                    'valor_total' => collect($items)->sum(fn($item) => $item['price'] * $item['quantity']),
                    'data' => now(),
                    'status' => 'aprovado',
                ]);

                foreach ($items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    TransactionItem::create([
                        'transacao_id' => $transaction->id,
                        'produto_id'   => $product->id,
                        'quantidade'   => $item['quantity'],
                        'valor_unitario' => $item['price'],
                    ]);

                    $product->decrement('quantidade', $item['quantity']);

                    $product->user->increment('saldo', $item['price'] * $item['quantity']);
                }
            });

            CartItem::where('user_id', $request->user()->id)->delete();

            $paymentLink = data_get($response->json(), 'links.1.href');
            return redirect()->away($paymentLink);
        }

        return redirect()->back()->withErrors('Erro ao criar checkout no PagSeguro.');
    }
}
