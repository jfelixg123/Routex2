<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'SimexRouteX')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 flex min-h-screen">
        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <main >
                @yield('content')
            </main>
        </div>

    </body>
</html>
