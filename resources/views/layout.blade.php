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
            <li><a href="/">{{__('Reports')}}</a></li>

            <!-- <li onclick="toggleSubmenu('transactions')">{{__('Transactions')}}</li>
            <ul class="submenu transactions">
                <li><a href="/">{{__('New Transactions')}}</a></li>
            </ul> -->

            <li onclick="toggleSubmenu('entries')">{{__('Entries')}}</li>
            <ul class="submenu entries">
                <li><a href="{{route('owner.list')}}">{{__('Owner')}}</a></li>
                <li><a href="{{route('payment-method.list')}}">{{__('Payment Method')}}</a></li>
                <li><a href="{{route('transaction-type.list')}}">{{__('Transaction Type')}}</a></li>
            </ul>
        </ul>
    </nav>

    <div class="base-page">
        <header class="topbar">
            <div class="menu-item" onclick="toggleNotificationsMenu()">Avisos
                <span class="badge" id="notificationCount">3</span>
                <ul class="topbar-dropdown notifications-options">
                    <li>Nova atualização disponível</li>
                    <li>Lembrete: reunião às 15h</li>
                    <li>Seu relatório foi aprovado</li>
                </ul>
            </div>
            <div class="menu-item" onclick="toggleDarkMode()">{{ __('Dark Mode') }}</div>
            <div class="menu-item" onclick="toggleLogoutMenu()">Logout
                <ul class="topbar-dropdown logout-options">
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
