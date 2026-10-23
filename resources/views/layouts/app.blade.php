<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon site')</title>
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>

<body>
    @include('layouts.partials.public-navigation')

    <main>
        @yield('content')
    </main>

    <footer></footer>

    @stack('scripts')
</body>

</html>
