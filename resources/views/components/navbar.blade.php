@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="navbar bg-base-100 shadow mb-6 px-4">
    <div class="flex-1">
        <a href="{{ route('index') }}" class="btn btn-ghost text-xl">Reserva de Aulas</a>
    </div>

    <div class="flex-none">
        <ul class="menu menu-horizontal px-1 gap-2 items-center">
            
        @auth
            {{-- Enlaces de administración (solo si tiene permisos, añade la lógica si aplica) --}}
            @if (Auth::check() && Auth::user()->rol === 'admin')
                <li><a href="{{ route('admin.dashboard') }}">Adminstracion</a></li>
            @endif
            {{-- Enlaces individuales --}}
                <li>
                    <a href="{{ route('perfil') }}" class="{{ $currentRoute === 'perfil' ? 'active font-semibold' : '' }}">
                        Perfil
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
                <li>
                    <span class="text-sm opacity-70 hidden md:inline">
                        {{ Auth::user()->name }}
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="{{ $currentRoute === 'login' ? 'active font-semibold' : '' }}">
                        Iniciar sesión
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="{{ $currentRoute === 'register' ? 'active font-semibold' : '' }}">
                        Registrarse
                    </a>
                </li>
        @endauth
            {{-- Selector de tema --}}
            <li>
                <label class="flex cursor-pointer gap-2 px-2">
                    {{-- Icono Sol --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5" />
                        <path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
                    </svg>

                    <input type="checkbox" class="toggle theme-controller" id="themeToggle" />

                    {{-- Icono Luna --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                </label>
            </li>
        </ul>
    </div>
</nav>
