<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use App\Exports\SalesExport;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // ─── USER PURCHASES PDF ──────────────────────────────────────────
    public function userPurchasesPdf(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::where('comprador_id', $user->id)
            ->with(['buyer', 'items.product.user'])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $transactions = $query->get();

        $pdf = Pdf::loadView('reports.purchases-pdf', [
            'transactions' => $transactions,
            'title' => 'Relatório de Compras',
            'userName' => $user->nome,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ]);

        return $pdf->download('compras-' . now()->format('Y-m-d') . '.pdf');
    }

    // ─── USER SALES PDF ──────────────────────────────────────────────
    public function userSalesPdf(Request $request)
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
                $q->with('product.user');
            }])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $transactions = $query->get();

        $pdf = Pdf::loadView('reports.sales-pdf', [
            'transactions' => $transactions,
            'title' => 'Relatório de Vendas',
            'userName' => $user->nome,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ]);

        return $pdf->download('vendas-' . now()->format('Y-m-d') . '.pdf');
    }

    // ─── ADMIN PURCHASES PDF ────────────────────────────────────────
    public function adminPurchasesPdf(Request $request)
    {
        $query = Transaction::with(['buyer', 'items.product.user'])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $transactions = $query->get();

        $pdf = Pdf::loadView('reports.purchases-pdf', [
            'transactions' => $transactions,
            'title' => 'Relatório de Compras (Admin)',
            'userName' => null,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ]);

        return $pdf->download('admin-compras-' . now()->format('Y-m-d') . '.pdf');
    }

    // ─── ADMIN PURCHASES EXCEL ──────────────────────────────────────
    public function adminPurchasesExcel(Request $request)
    {
        return Excel::download(
            new PurchasesExport($request->start_date, $request->end_date),
            'admin-compras-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ─── ADMIN SALES PDF ────────────────────────────────────────────
    public function adminSalesPdf(Request $request)
    {
        $query = Transaction::with(['buyer', 'items.product.user'])
            ->orderByDesc('data');

        if ($request->filled('start_date')) {
            $query->whereDate('data', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('data', '<=', $request->end_date);
        }

        $transactions = $query->get();

        $pdf = Pdf::loadView('reports.sales-pdf', [
            'transactions' => $transactions,
            'title' => 'Relatório de Vendas (Admin)',
            'userName' => null,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ]);

        return $pdf->download('admin-vendas-' . now()->format('Y-m-d') . '.pdf');
    }

    // ─── ADMIN SALES EXCEL ──────────────────────────────────────────
    public function adminSalesExcel(Request $request)
    {
        return Excel::download(
            new SalesExport($request->start_date, $request->end_date),
            'admin-vendas-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
