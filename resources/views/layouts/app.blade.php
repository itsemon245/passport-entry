<!DOCTYPE html>
<html x-data="data()" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', $title) }}</title>

    @notifyCss
    @yield('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="{{ asset('assets/css/tailwind.output.css') }}" rel="stylesheet">
    </link>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js" defer></script>
    <script src="{{ asset('assets/js/init-alpine.js') }}"></script>
    <script src="https://unpkg.com/htmx.org@1.9.6"
        integrity="sha384-FhXw7b6AlE/jyjlZH5iHa/tTe9EpJ1Y55RjcgPbjeWMskSxZt1v9qkxLJWNJaGni" crossorigin="anonymous">
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])



</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('layouts.sidebar')
        <div class="flex flex-col flex-1 w-full">
            @include('layouts.header')
            <main class="h-full overflow-y-auto">
                <div class="container px-6 print:px-0 mx-auto grid mb-10">
                    <div class="flex gap-4 items-center mt-5 print:hidden">
                        <!-- Modal toggle -->
                        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 print:hidden">
                            {{ str($title)->lower() == 'dashboard' ? '' : $title }}
                        </h2>
                        @yield('actions')

                    </div>

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <x-notify::notify />
    @notifyJs
    @yield('scripts')
</body>

</html>
