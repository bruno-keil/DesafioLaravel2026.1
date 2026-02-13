<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Vendas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }
        .header { background: linear-gradient(135deg, #0a0f16 0%, #1a1a2e 100%); color: #fff; padding: 30px; margin-bottom: 20px; }
        .header h1 { font-size: 24px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 4px; }
        .header p { font-size: 11px; opacity: 0.7; }
        .header .badge { display: inline-block; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #10b981; padding: 3px 10px; border-radius: 20px; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; margin-top: 6px; }
        .meta { padding: 0 30px; margin-bottom: 20px; }
        .meta table { width: 100%; }
        .meta td { padding: 4px 0; font-size: 11px; }
        .meta .label { color: #6b7280; width: 130px; }
        .content { padding: 0 30px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th { background: #1a1a2e; color: #fff; padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }
        table.data td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f9fafb; }
        .transaction-header { background: #f3f4f6; padding: 10px 15px; margin-top: 15px; border-left: 3px solid #10b981; }
        .transaction-header h3 { font-size: 12px; color: #1a1a2e; }
        .transaction-header span { font-size: 10px; color: #6b7280; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-pago { background: #d1fae5; color: #065f46; }
        .status-pendente { background: #fef3c7; color: #92400e; }
        .status-cancelado { background: #fee2e2; color: #991b1b; }
        .total-row { font-weight: bold; background: #f0fdf4 !important; }
        .total-row td { border-top: 2px solid #10b981; }
        .summary { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px 20px; margin: 20px 30px; }
        .summary h3 { color: #065f46; font-size: 13px; margin-bottom: 8px; }
        .summary .total { font-size: 20px; color: #10b981; font-weight: bold; }
        .footer { text-align: center; color: #9ca3af; font-size: 9px; padding: 20px; margin-top: 20px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
        @if($userName)
            <span class="badge">{{ $userName }}</span>
        @endif
        @if($startDate || $endDate)
            <p style="margin-top: 6px; font-size: 10px;">
                Período:
                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Início' }}
                até
                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Hoje' }}
            </p>
        @endif
    </div>

    <div class="content">
        @php $grandTotal = 0; @endphp
        @foreach($transactions as $transaction)
            @php
                $transactionTotal = $transaction->items->sum(fn($item) => $item->quantidade * $item->valor_unitario);
                $grandTotal += $transactionTotal;
                $statusClass = 'status-' . strtolower($transaction->status);
            @endphp
            <div class="transaction-header">
                <h3>
                    Venda #{{ $transaction->id }}
                    <span class="status {{ $statusClass }}">{{ ucfirst($transaction->status) }}</span>
                </h3>
                <span>{{ $transaction->data->format('d/m/Y H:i') }} &bull; Comprador: {{ $transaction->buyer->nome ?? 'Removido' }}</span>
            </div>
            <table class="data">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Comprador</th>
                        <th>Qtd</th>
                        <th>Valor Unit.</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                        <tr>
                            <td>{{ $item->product->nome ?? 'Produto removido' }}</td>
                            <td>{{ $transaction->buyer->nome ?? 'Removido' }}</td>
                            <td>{{ $item->quantidade }}</td>
                            <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                            <td style="text-align:right">R$ {{ number_format($item->quantidade * $item->valor_unitario, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4">Total da Transação</td>
                        <td style="text-align:right">R$ {{ number_format($transactionTotal, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    </div>

    <div class="summary">
        <h3>Resumo</h3>
        <p>Total de transações: <strong>{{ $transactions->count() }}</strong></p>
        <p class="total">Total Geral: R$ {{ number_format($grandTotal, 2, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>LootBay &mdash; Relatório gerado automaticamente &mdash; {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
