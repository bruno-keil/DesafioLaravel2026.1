<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::where('comprador_id', $user->id)
            ->with(['items.product'])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $purchases = $query->paginate(15)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }
}
