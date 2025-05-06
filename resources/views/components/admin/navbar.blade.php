@php
    $currentRoute = Route::currentRouteName();
@endphp
<nav class="navbar bg-base-100 shadow mb-6 px-4">
    <div class="flex-1">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-xl">Panel de Administración</a>
    </div>

    <div class="flex-none">
        <ul class="menu menu-horizontal gap-2 px-1 items-center">
            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ $currentRoute === 'admin.users.index' ? 'active font-semibold' : '' }}">
                    Gestion de usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.create') }}"
                    class="{{ $currentRoute === 'admin.users.create' ? 'active font-semibold' : '' }}">
                    Creacion de usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.horarios-clase.form') }}"
                    class="{{ $currentRoute === 'admin.horarios.import' ? 'active font-semibold' : '' }}">
                    Importar horarios de clase
                </a>
            </li>
            <li>
                <a href="{{ route('admin.dias-no-lectivos.form') }}"
                    class="{{ $currentRoute === 'admin.dias-no-lectivos.import' ? 'active font-semibold' : '' }}">
                    Importar dias no lectivos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.aulas.index') }}"
                    class="{{ $currentRoute === 'admin.dias-no-lectivos.import' ? 'active font-semibold' : '' }}">
                    Creacion de aulas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.horarios.index') }}"
                    class="{{ $currentRoute === 'admin.dias-no-lectivos.import' ? 'active font-semibold' : '' }}">
                    Franjas horarias
                </a>
            </li>
            {{-- Añadir más enlaces aquí conforme se vayan creando --}}
            <li><a href="{{ route('index') }}">Volver</a></li>

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