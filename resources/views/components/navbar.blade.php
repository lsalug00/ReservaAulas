@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="navbar bg-base-100 shadow md:mb-6 px-2 md:px-4">
    {{-- Logo --}}
    <div class="flex-1">
        <a href="{{ route('index') }}" class="btn btn-ghost text-xl">Reserva de Aulas</a>
    </div>

    
    {{-- Menú horizontal en md+ --}}
    <div class="hidden md:flex flex-none">
        <ul class="menu menu-horizontal gap-2 items-center">
            @auth
            @if (Auth::user()->rol === 'admin')
            <li><a href="{{ route('admin.dashboard') }}">Administración</a></li>
            @endif
            <li><a href="{{ route('perfil') }}" class="{{ $currentRoute === 'perfil' ? 'active font-semibold' : '' }}">Perfil</a></li>
            <li>
                    <button
                        type="submit"
                        form="logout-form"
                        class="cursor-pointer"
                    >
                        Cerrar sesión
                    </button>
                </li>
                <li><span class="text-sm opacity-70 hidden md:inline">{{ Auth::user()->name }}</span></li>
            @else
            <li><a href="{{ route('login') }}" class="{{ $currentRoute === 'login' ? 'active font-semibold' : '' }}">Iniciar sesión</a></li>
                <li><a href="{{ route('register') }}" class="{{ $currentRoute === 'register' ? 'active font-semibold' : '' }}">Registrarse</a></li>
            @endauth
        </ul>
    </div>
    
    {{-- Tema --}}
    <div class="flex justify-center items-center gap-2">
        {{-- Sol --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
            <circle cx="12" cy="12" r="5"/>
            <path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/>
        </svg>

        <input type="checkbox" class="toggle theme-controller" id="themeToggle"/>

        {{-- Luna --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </div>

    {{-- Menu hamburgesa para móvil --}}
    <div class="dropdown dropdown-end md:hidden">
        <label tabindex="0" class="btn btn-ghost btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </label>
        <ul tabindex="0" class="dropdown-content mt-3 z-[1] p-2 shadow bg-base-300 rounded-box w-52">
            @auth
                @if (Auth::user()->rol === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}">Administración</a></li>
                @endif
                <li><a href="{{ route('perfil') }}">Perfil</a></li>
                <li>
                    <button
                        type="submit"
                        form="logout-form"
                        class="cursor-pointer"
                    >
                        Cerrar sesión
                    </button>
                </li>
            @else
                <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                <li><a href="{{ route('register') }}">Registrarse</a></li>
            @endauth
        </ul>
    </div>
    
    {{-- Form logout oculto --}}
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>
</nav>
