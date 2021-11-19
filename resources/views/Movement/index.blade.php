@extends('layout')

@section('header')
Movimentos
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
        @endforeach
    </div>
@endif
@if(!empty($message))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<br />
<div class="container">
    <div class="row">
        <div class="col">
            <ul class="list-group">
                @foreach($movements as $movement)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $movement->movement_date)->format('d/m/Y') }} | {{ $movement->net_value->formatMoney() }} <br />
                    {{ $movement->title }}
                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='';">
                        <form method="post" action=""
                            onsubmit="return confirm('Tem certeza que deseja remover este movimento?')">
                            @csrf
                            <input class="btn btn-primary" type="submit" value="Excluir" style="background-color: red;border-color: red;">
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection