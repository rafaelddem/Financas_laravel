@extends('layout')

@section('page_content')
        <section class="presentation">
            <h1>Bem-vindo ao Sistema Financeiro</h1>
            <p>Este sistema ajuda a gerenciar seus dados financeiros de forma eficiente e segura.</p>
        </section>

        <section class="cards">
            <div class="card">
                <h2 class="card-title">Total Receitas</h2>
                <p class="card-value">R$ 120.000,00</p>
            </div>
            <div class="card">
                <h2 class="card-title">Total Despesas</h2>
                <p class="card-value">R$ 45.500,00</p>
            </div>
            <div class="card">
                <h2 class="card-title">Lucro Líquido</h2>
                <p class="card-value">R$ 74.500,00</p>
            </div>
        </section>

        <section class="presentation">
            <h2 class="card-title">Relatório Financeiro</h2>
            <canvas id="lineChart"></canvas>
        </section>
@endsection