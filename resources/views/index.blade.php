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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <header class="topbar">
        <div class="menu-item" onclick="toggleDarkMode()"><i id="darkModeIcon" class="fa-solid fa-moon"></i></div>
    </header>

    <div class="centered">
        <div class="flex-container">
            <div class="col">
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
        </section>
    </div>
    </div>

        @yield('page_content')
    </div>

    @yield('scripts')
</body>
</html>
