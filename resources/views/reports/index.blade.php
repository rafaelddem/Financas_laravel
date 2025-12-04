@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>Bem-vindo ao Sistema Financeiro</h1>
        <p>Este sistema ajuda a gerenciar seus dados financeiros de forma eficiente e segura.</p>
    </div>

    <div class="flex-container">
        <div class="col_25 md_col_50 sm_col">
            <div class="cards">
                <div class="card">
                    <h2 class="card-title">{{__('Total Amount')}}</h2>
                    <p class="card-value">{{ \App\Helpers\MoneyHelper::format($income) }}</p>
                </div>
            </div>
        </div>
        <div class="col_25 md_col_50 sm_col">
            <div class="cards">
                <div class="card">
                    <h2 class="card-title">{{__('Future Invoices Value')}}</h2>
                    <p class="card-value">{{ \App\Helpers\MoneyHelper::format($future_credit_value) }}</p>
                </div>
            </div>
        </div>
        <div class="col_25 md_col_50 sm_col">
            <div class="cards">
                <div class="card">
                    <h2 class="card-title">{{__('Amounts to Received')}}</h2>
                    <p class="card-value">{{ \App\Helpers\MoneyHelper::format($total_loans['positive']) }}</p>
                </div>
            </div>
        </div>
        <div class="col_25 md_col_50 sm_col">
            <div class="cards">
                <div class="card">
                    <h2 class="card-title">{{__('Amounts to Refunded')}}</h2>
                    <p class="card-value">{{ \App\Helpers\MoneyHelper::format($total_loans['negative']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="presentation">
        <div class="flex-container">
            <div class="col_66">
                <h2>{{__('Wallet Totals')}}</h2>
                <div class="canvas-wrapper h_25">
                    @include('components.bar-chart', [
                        'name' => 'totalByWallets',
                        'hasLabel' => false,
                        'aspectRatio' => 4,
                        'labels' => $wallets_values['label'],
                        'values' => $wallets_values['value']
                    ])
                </div>
            </div>

            @if(count($loans) > 0)
                <div class="col_33">
                    <h2>{{__('Wallet Loans')}}</h2>
                    <div class="canvas-wrapper h_50">
                        @include('components.bar-chart', [
                            'name' => 'loans',
                            'hasLabel' => false,
                            'labels' => $loans['label'],
                            'values' => $loans['value']
                        ])
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <h2>{{__('Totals By Period')}}</h2>
                <div class="canvas-wrapper h_25">
                    @include('components.line-chart', [
                        'name' => 'income_by_period',
                        'aspectRatio' => 4,
                        'labels' => $income_by_period['labels'],
                        'values' => $income_by_period['values'],
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
