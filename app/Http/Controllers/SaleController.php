<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class SaleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sales = Transaction::whereHas('items', function ($query) use ($user) {
                $query->whereHas('product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->with(['buyer', 'items' => function ($query) use ($user) {
                $query->whereHas('product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
                $query->with('product');
            }])
            ->orderByDesc('data')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }
}
