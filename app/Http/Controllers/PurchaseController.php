<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $purchases = Transaction::where('comprador_id', $user->id)
            ->with(['items.product'])
            ->orderByDesc('data')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }
}
