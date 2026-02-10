<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);

        $allUsers = User::all();
        
        foreach ($allUsers as $user) {
            $userProducts = Product::where('user_id', $user->id)->get();
            
            if ($userProducts->isEmpty()) {
                continue;
            }

            $salesCount = ($user->email === 'teste@email.com') ? 30 : 10;

            for ($i = 0; $i < $salesCount; $i++) {
                $productToSell = $userProducts->random();
                
                $buyer = $allUsers->where('id', '!=', $user->id)->random();

                $date = now()->subDays(rand(0, 90));
                
                $quantity = rand(1, 5);
                $totalValue = $productToSell->preco * $quantity;

                $transaction = Transaction::create([
                    'comprador_id' => $buyer->id,
                    'valor_total' => $totalValue,
                    'data' => $date,
                    'status' => 'concluido',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                TransactionItem::create([
                    'transacao_id' => $transaction->id,
                    'produto_id' => $productToSell->id,
                    'quantidade' => $quantity,
                    'valor_unitario' => $productToSell->preco,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}