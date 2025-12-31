<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APPLICATION_NAME')}}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    @stack('head-script')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <header class="topbar">
        <div class="menu-toggle-mobile"><i class="fa-solid fa-bars"></i></div>
        <div class="menu-item" onclick="toggleNotificationsMenu()">Avisos
            <span class="badge" id="notificationCount">3</span>
            <ul class="topbar-dropdown notifications-options">
                <li>Nova atualização disponível</li>
                <li>Lembrete: reunião às 15h</li>
                <li>Seu relatório foi aprovado</li>
            </ul>
        </div>
        <div class="menu-item" onclick="toggleDarkMode()"><i id="darkModeIcon" class="fa-solid fa-moon"></i></div>
    </header>
    <nav class="sidebar">
        <div class="menu-scroll">
            <ul class="menu-main">
                <li class="navbar-item"><a href="{{route('home')}}"><i class="fa-solid fa-house"></i><span>{{__('Dashboard')}}</span></a></li>
                <li class="navbar-item" onclick="toggleSubmenu(event, 'reports')"><i class="fa-solid fa-file-lines"></i><span>{{__('Reports')}}</span><i class="fa-solid fa-caret-left"></i></li>
                <ul class="submenu reports">
                    <li class="navbar-item"><a href="{{route('reports.loans')}}"><i class="fa-solid fa-file-code"></i><span>{{__('Wallet Loans')}}</span></a></li>
                </ul>
                <li class="navbar-item" onclick="toggleSubmenu(event, 'transactions')"><i class="fa-solid fa-piggy-bank"></i><span>{{__('Transactions')}}</span><i class="fa-solid fa-caret-left"></i></li>
                <ul class="submenu transactions">
                    <li class="navbar-item"><a href="{{route('invoice.list')}}"><i class="fa-solid fa-file-invoice-dollar"></i><span>{{__('Invoices')}}</span></a></li>
                    <li class="navbar-item"><a href="{{route('transaction.list')}}"><i class="fa-solid fa-money-bill-transfer"></i><span>{{__('Transaction Entry')}}</span></a></li>
                    <li class="navbar-item"><a href="{{route('extract-import.index')}}"><i class="fa-solid fa-file-import"></i><span>{{__('Extract Import')}}</span></a></li>

                    @can('admin')
                        <li class="navbar-item"><a href="{{route('transaction-base.list')}}"><i class="fa-solid fa-diagram-predecessor"></i><span>{{__('Transaction Base')}}</span></a></li>
                    @endcan
                </ul>
                @can('admin')
                <li class="navbar-item" onclick="toggleSubmenu(event, 'entries')"><i class="fa-solid fa-file-circle-plus"></i><span>{{__('Entries')}}</span><i class="fa-solid fa-caret-left"></i></li>
                <ul class="submenu entries">
                    <li class="navbar-item"><a href="{{route('owner.list')}}"><i class="fa-solid fa-user-plus"></i><span>{{__('Owner')}}</span></a></li>
                    <li class="navbar-item"><a href="{{route('payment-method.list')}}"><i class="fa-solid fa-money-check-dollar"></i><span>{{__('Payment Method')}}</span></a></li>
                    <li class="navbar-item"><a href="{{route('category.list')}}"><i class="fa-solid fa-icons"></i><span>{{__('Category')}}</span></a></li>
                </ul>
                @endcan
            </ul>
        </div>
        <div class="menu-final">
            <ul>
                <li class="navbar-item" onclick="toggleSubmenu(event, 'login')"><i class="fa-solid fa-user"></i><span>{{__('Profile')}}</span><i class="fa-solid fa-caret-left"></i></li>
                <ul class="submenu login">
                    <li class="navbar-item"><a href="/"><i class="fa-solid fa-gear"></i><span>{{__('Configurations')}}</span></a></li>
                    <li class="navbar-item"><a href="{{route('sign_out')}}"><i class="fa-solid fa-right-from-bracket"></i><span>{{__('Logout')}}</span></a></li>
                </ul>
            </ul>
        </div>
    </nav>

    <div class="base-page">
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

    @yield('scripts')
</body>
</html>
