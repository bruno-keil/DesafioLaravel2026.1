<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $adminChart = null;
        $userChart = null;

        if (auth()->user()->is_admin) {
            $adminChart = new LaravelChart([
                'chart_title' => 'Novos Produtos (Mensal)',
                'report_type' => 'group_by_date',
                'model' => 'App\Models\Product',
                'group_by_field' => 'created_at',
                'group_by_period' => 'month',
                'chart_type' => 'bar',
                'filter_field' => 'created_at',
                'filter_days' => 365,
                'chart_color' => '60, 150, 255',
            ]);
        } else {
            $userChart = new LaravelChart([
                'chart_title' => 'Vendas Realizadas (Mensal)',
                'report_type' => 'group_by_date',
                'model' => 'App\Models\Transaction',
                'group_by_field' => 'created_at',
                'group_by_period' => 'month',
                'chart_type' => 'line',
                'filter_field' => 'created_at',
                'filter_days' => 365,
                'conditions' => [
                    [
                        'name' => 'Vendas',
                        'condition' => 'id IN (SELECT transacao_id FROM itens_transacoes JOIN produtos ON itens_transacoes.produto_id = produtos.id WHERE produtos.user_id = ' . auth()->id() . ')',
                        'color' => '#34d399',
                        'fill' => false,
                    ]
                ]
            ]);
        }

        return view('dashboard', compact('adminChart', 'userChart'));
    }
}