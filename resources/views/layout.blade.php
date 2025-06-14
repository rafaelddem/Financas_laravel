<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APPLICATION_NAME')}}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    @stack('head-script')
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <nav class="sidebar">
        <ul>
            <li><a href="/">{{__('Dashboard')}}</a></li>
            <li>Relatórios</li>
            <li><a href="{{route('transaction.list')}}">{{__('Transaction Entry')}}</a></li>
            <li onclick="toggleSubmenu()">Cadastros</li>
            <ul class="submenu">
                <li><a href="{{route('owner.list')}}">{{__('Owner')}}</a></li>
                <li><a href="{{route('payment-method.list')}}">{{__('Payment Method')}}</a></li>
                <li><a href="{{route('transaction-type.list')}}">{{__('Transaction Type')}}</a></li>
            </ul>
        </ul>
    </nav>
    
    <div class="base-page">
        <header class="topbar">
            <div class="notifications-menu" onclick="toggleNotificationsMenu()">Avisos
                <span class="badge" id="notificationCount">3</span>
                <ul class="dropdown-menu notifications-options">
                    <li>Nova atualização disponível</li>
                    <li>Lembrete: reunião às 15h</li>
                    <li>Seu relatório foi aprovado</li>
                </ul>
            </div>
            <div class="menu-item" onclick="toggleDarkMode()">Modo Escuro</div>
            <div class="menu-item" onclick="toggleLogoutMenu()">Logout
                <ul class="dropdown-menu logout-options">
                    <li>Perfil</li>
                    <li>Sair</li>
                </ul>
            </div>
        </header>
        
        <section class="alerts">
            @if ($errors->any())
                <div id="alertBox" class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br />
                    @endforeach
                </div>
            @endif

            @if(!empty($message))
            <div id="alertBox" class="alert alert-success">
                {{ $message }}
            </div>
            @endif

            <!-- <div class="alert alert-warning">⚠️ Atenção: Este é um aviso de alerta!</div> -->
        </section>

        @yield('page_content')
    </div>
</body>
</html>
