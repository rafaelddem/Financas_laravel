<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APPLICATION_NAME')}}</title>
    <style>{{file_get_contents(public_path('css/report_styles.css'))}}</style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <div class="base-page">
        <div class="presentation">
            <h1>{{ __('Wallet Loans') }}</h1>
            <h4>{{__('Name')}}: {{$owner_name}}</h4>
            <h4>{{__('Period Between :start_date and :end_date', ["start_date" => $start_date->format('d/m/Y'), "end_date" => $end_date->format('d/m/Y')])}}</h4>
        </div>

        <div class="presentation">
            <h2 class="card-title">{{__('Transactions')}}</h2>
            @php($in = $out = $parcial = 0)
            <table>
                <!-- <thead>
                    <tr>
                        <th>{{__('Transaction Date')}}</th>
                        <th>{{__('Source Wallet')}} > {{__('Destination Wallet')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('In')}}</th>
                        <th>{{__('Out')}}</th>
                    </tr>
                </thead> -->
                <tbody>
                @if($ownerLoans['beforePeriod'] != 0)
                    @php($parcial = $ownerLoans['beforePeriod'])
                    <tr>
                        <td colspan="3" class="right"><b>{{__('Amounts Before Period')}}</b></td>
                        <td colspan="2" class="left"><b>{{ \App\Helpers\MoneyHelper::format($ownerLoans['beforePeriod'] ?? 0) }}</b></td>
                    </tr>
                @endif
                @foreach($ownerLoans['fromPeriod'] as $month => $transactionsByMonth)
                    <tr class="table_header">
                        <td colspan="5">
                            {{__('Period :reference', ['reference' => $month])}}
                        </td>
                    </tr>
                    @foreach($transactionsByMonth as $transaction)
                        <tr>
                            <td class="table_col_10">{{ $transaction['date']->format('d/m/Y') }}</td>
                            <td class="left table_col_20">
                                {{ $transaction['source_owner_name'] }} > {{ $transaction['destination_owner_name'] }}<br>
                                {{__(\App\Enums\PaymentType::translate($transaction['payment_methods_type']))}}
                            </td>
                            <td class="left table_col_50">{{ $transaction['transactions_title'] }}</td>
                            <td class="table_col_10">
                                @if ($transaction['source_owner_id'] == env('MY_OWNER_ID'))
                                    @php($in += $transaction['net_value'])
                                    {{ \App\Helpers\MoneyHelper::format($transaction['net_value']) }}
                                @endif
                            </td>
                            <td class="table_col_10">
                                @if ($transaction['source_owner_id'] != env('MY_OWNER_ID'))
                                    @php($out += $transaction['net_value'])
                                    {{ \App\Helpers\MoneyHelper::format($transaction['net_value']) }}
                                    @if ($transaction['source_owner_id'] != env('MY_OWNER_ID') && $transaction['destination_owner_id'] != env('MY_OWNER_ID')) * @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @php($parcial += ($in - $out))
                    <tr class="table_footer">
                        <td colspan="3" class="right"><b>{{__('Amount Until End Date')}}</b></td>
                        <td colspan="2" class="left"><b>{{ \App\Helpers\MoneyHelper::format($parcial) }}</b></td>
                    </tr>
                    @php($in = $out = 0)
                @endforeach
                </tbody>
            </table>
            <p>{{__('Observations')}}:</p>
            <p>* {{__('Value correction movement. It will not necessarily be displayed in the corresponding column. Pay attention to the source and destination wallets for better understanding.')}}</p>
            <p>** {{__('Regarding the totals, when the value shown is positive, it means I should receive that amount, and when it is negative, it means I should return it.')}}</p>
        </div>
    </div>
</body>
</html>
