<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function purchases(Request $request)
    {
        $query = Transaction::with(['buyer', 'items.product.user'])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $purchases = $query->paginate(15)->withQueryString();

        return view('admin.purchases.index', compact('purchases'));
    }

    public function sales(Request $request)
    {
        $query = Transaction::with(['buyer', 'items.product.user'])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $sales = $query->paginate(15)->withQueryString();

        return view('admin.sales.index', compact('sales'));
    }
}
