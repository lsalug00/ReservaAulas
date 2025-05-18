@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="navbar bg-base-100 shadow md:mb-6 px-2 md:px-4">
    {{-- Logo --}}
    <div class="flex-1">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-xl">Panel de Administración</a>
    </div>

    {{-- Menú horizontal en escritorio --}}
    <div class="hidden md:flex flex-none">
        <ul class="menu menu-horizontal gap-2 items-center">
            <li>
                {{-- Dropdown Usuarios --}}
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="cursor-pointer {{ in_array($currentRoute, ['admin.users.index', 'admin.users.create']) ? 'active font-bold' : '' }}">
                        Usuarios
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-200 rounded-box w-52">
                        <li><a href="{{ route('admin.users.index') }}" class="{{ $currentRoute === 'admin.users.index' ? 'active font-bold' : '' }}">Gestión de usuarios</a></li>
                        <li><a href="{{ route('admin.users.create') }}" class="{{ $currentRoute === 'admin.users.create' ? 'active font-bold' : '' }}">Creación de usuarios</a></li>
                    </ul>
                </div>
            </li>
            <li>
                {{-- Dropdown Importaciones --}}
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="cursor-pointer {{ in_array($currentRoute, ['admin.horarios-clase.form', 'admin.dias-no-lectivos.form']) ? 'active font-bold' : '' }}">
                        Importaciones
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-200 rounded-box w-52">
                        <li><a href="{{ route('admin.horarios-clase.form') }}" class="{{ $currentRoute === 'admin.horarios-clase.form' ? 'active font-bold' : '' }}">Importar horarios de clase</a></li>
                        <li><a href="{{ route('admin.dias-no-lectivos.form') }}" class="{{ $currentRoute === 'admin.dias-no-lectivos.form' ? 'active font-bold' : '' }}">Importar días no lectivos</a></li>
                    </ul>
                </div>
            </li>
            <li>
                {{-- Dropdown Aulas y Horarios --}}
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="cursor-pointer {{ in_array($currentRoute, ['admin.aulas.index', 'admin.aulas.manage', 'admin.horarios.index']) ? 'active font-bold' : '' }}">
                        Aulas y Horarios
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-200 rounded-box w-52">
                        <li><a href="{{ route('admin.aulas.index') }}" class="{{ $currentRoute === 'admin.aulas.index' ? 'active font-bold' : '' }}">Creación de aulas</a></li>
                        <li><a href="{{ route('admin.aulas.manage') }}" class="{{ $currentRoute === 'admin.aulas.manage' ? 'active font-bold' : '' }}">Gestión de aulas</a></li>
                        <li><a href="{{ route('admin.horarios.index') }}" class="{{ $currentRoute === 'admin.horarios.index' ? 'active font-bold' : '' }}">Franjas horarias</a></li>
                    </ul>
                </div>
            </li>
            <li>
                {{-- Dropdown Otros --}}
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="cursor-pointer">
                        {{ Auth::user()->name }}
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-200 rounded-box w-52">
                        <li><a href="{{ route('index') }}">Volver</a></li>
                        <li>
                            {{-- Cierre sesión con botón --}}
                            <button
                                type="submit"
                                form="logout-form"
                                class="cursor-pointer"
                            >
                                Cerrar sesión
                            </button>
                        </li>
                    </ul>
                </div>
            </li>
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

    {{-- Dropdown móvil --}}
    <div class="dropdown dropdown-end md:hidden">
        <label tabindex="0" class="btn btn-ghost btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </label>
        <ul tabindex="0" class="menu dropdown-content mt-3 z-[1] p-2 shadow bg-base-200 rounded-box w-52">
            <li><a href="{{ route('admin.users.index') }}">Gestión de usuarios</a></li>
            <li><a href="{{ route('admin.users.create') }}">Creación de usuarios</a></li>
            <li><a href="{{ route('admin.horarios-clase.form') }}">Importar horarios de clase</a></li>
            <li><a href="{{ route('admin.dias-no-lectivos.form') }}">Importar días no lectivos</a></li>
            <li><a href="{{ route('admin.aulas.index') }}">Creación de aulas</a></li>
            <li><a href="{{ route('admin.aulas.manage') }}">Gestión de aulas</a></li>
            <li><a href="{{ route('admin.horarios.index') }}">Franjas horarias</a></li>
            <li><a href="{{ route('index') }}">Volver</a></li>
            <li>
                <button
                    type="submit"
                    form="logout-form"
                    class="cursor-pointer"
                >
                    Cerrar sesión
                </button>
            </li>
        </ul>
    </div>

    {{-- Form logout oculto --}}
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>
</nav>
