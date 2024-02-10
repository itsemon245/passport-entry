<!-- Desktop sidebar -->
<div class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0 print:hidden">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">
            {{ env('APP_NAME') }}
        </a>
        <ul>
            @foreach (config('sidebar') as $item => $route)
                @if (auth()->user()->is_admin)
                   @if(!$route->isUserOnly)
                        <li class="relative px-6 py-3">
                        @if (request()->routeIs($route->name))
                            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                                aria-hidden="true"></span>
                        @endif
                        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs($route->name) ? 'dark:text-gray-100' : '' }}"
                            href="{{ route($route->name) }}">
                            {!! Blade::render($route->icon) !!}
                            <span class="ml-4">{{ $item }}</span>
                        </a>
                    </li>
                   @endif
                @else
                    @if (!$route->isAdminOnly)
                        <li class="relative px-6 py-3">
                            @if (request()->routeIs($route->name))
                                <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                                    aria-hidden="true"></span>
                            @endif
                            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs($route->name) ? 'dark:text-gray-100' : '' }}"
                                href="{{ route($route->name) }}">
                                {!! Blade::render($route->icon) !!}
                                <span class="ml-4">{{ $item }}</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>
</div>
<!-- Mobile sidebar -->
<!-- Backdrop -->
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
<aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
    x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu"
    @keydown.escape="closeSideMenu">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">
            {{ env('APP_NAME') }}
        </a>
        <ul class="mt-6">
            @foreach (config('sidebar') as $item => $route)
                @if (auth()->user()->is_admin)
                    <li class="relative px-6 py-3">
                        @if (request()->routeIs($route->name))
                            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                                aria-hidden="true"></span>
                        @endif
                        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs($route->name) ? 'dark:text-gray-100' : '' }}"
                            href="{{ route($route->name) }}">
                            {!! Blade::render($route->icon) !!}
                            <span class="ml-4">{{ $item }}</span>
                        </a>
                    </li>
                @else
                    @if (!$route->isAdminOnly)
                        <li class="relative px-6 py-3">
                            @if (request()->routeIs($route->name))
                                <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                                    aria-hidden="true"></span>
                            @endif
                            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs($route->name) ? 'dark:text-gray-100' : '' }}"
                                href="{{ route($route->name) }}">
                                {!! Blade::render($route->icon) !!}
                                <span class="ml-4">{{ $item }}</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>
</aside>
