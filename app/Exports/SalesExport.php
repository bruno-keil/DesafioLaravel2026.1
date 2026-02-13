<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Transaction::with(['buyer', 'items.product.user'])
            ->orderByDesc('data');

        if ($this->startDate) {
            $query->whereDate('data', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('data', '<=', $this->endDate);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Transação',
            'Data',
            'Comprador',
            'Vendedor(es)',
            'Produtos',
            'Qtd Itens',
            'Valor Total',
            'Status',
        ];
    }

    public function map($transaction): array
    {
        $products = $transaction->items->map(function ($item) {
            return $item->product ? $item->product->nome : 'Produto removido';
        })->implode(', ');

        $sellers = $transaction->items->map(function ($item) {
            return $item->product && $item->product->user ? $item->product->user->nome : 'Vendedor removido';
        })->unique()->implode(', ');

        return [
            $transaction->id,
            $transaction->data->format('d/m/Y H:i'),
            $transaction->buyer->nome ?? 'Usuário removido',
            $sellers,
            $products,
            $transaction->items->sum('quantidade'),
            'R$ ' . number_format($transaction->valor_total, 2, ',', '.'),
            ucfirst($transaction->status),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
