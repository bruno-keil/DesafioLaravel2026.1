<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::whereHas('items', function ($q) use ($user) {
                $q->whereHas('product', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
            })
            ->with(['buyer', 'items' => function ($q) use ($user) {
                $q->whereHas('product', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
                $q->with('product');
            }])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $sales = $query->paginate(15)->withQueryString();

        return view('sales.index', compact('sales'));
    }
}
