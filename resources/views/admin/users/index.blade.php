@extends('layouts.admin')

@section('title', 'Gestión de usuarios')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold mb-4">👥 Gestión de Usuarios</h1>

    @if (session('success'))
        <div class="alert alert-success mb-6 shadow-lg relative">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 close-alert">✕</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mb-6 shadow-lg relative">
            <div>
                <p class="font-bold">Se encontraron errores:</p>
                <ul class="text-sm mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 close-alert">✕</button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.index') }}" class="mb-6 space-y-2">
        @csrf
        <div class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ old('search',$searchOld) }}" placeholder="Buscar por nombre o email" class="input input-bordered flex-1">
            <select name="rol" class="select select-bordered">
                <option value="">Todos los roles</option>
                <option value="admin" {{ old('rol',$rolOld) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="profesor" {{ old('rol',$rolOld) == 'profesor' ? 'selected' : '' }}>Profesor</option>
                <option value="invitado" {{ old('rol',$rolOld) == 'invitado' ? 'selected' : '' }}>Invitado</option>
            </select>
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </div>
    </form>

    @php
        // $ordenActual = request('orden');
        $direccionActual = request('direccion', 'asc');

        function ordenarPor($columna) {
            $direccion = request('orden') === $columna && request('direccion') === 'asc' ? 'desc' : 'asc';
            return array_merge(request()->all(), ['orden' => $columna, 'direccion' => $direccion]);
        }

        function iconoOrdenSvg($columna) {
            if (request('orden') !== $columna) return '';

            return request('direccion') === 'asc'
                ? '<svg class="inline w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 6l-5 6h10l-5-6z" /></svg>'
                : '<svg class="inline w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 14l5-6H5l5 6z" /></svg>';
        }
    @endphp

    <table class="table w-full">
        <thead>
            <tr>
                <th><a href="{{ route('admin.users.index', ordenarPor('id')) }}">ID {!! iconoOrdenSvg('id') !!}</a></th>
                <th><a href="{{ route('admin.users.index', ordenarPor('name')) }}">Nombre {!! iconoOrdenSvg('name') !!}</a></th>
                <th><a href="{{ route('admin.users.index', ordenarPor('surname')) }}">Apellidos {!! iconoOrdenSvg('surname') !!}</a></th>
                <th><a href="{{ route('admin.users.index', ordenarPor('email')) }}">Email {!! iconoOrdenSvg('email') !!}</a></th>
                <th><a href="{{ route('admin.users.index', ordenarPor('codigo')) }}">Código {!! iconoOrdenSvg('codigo') !!}</a></th>
                <th><a href="{{ route('admin.users.index', ordenarPor('rol')) }}">Rol {!! iconoOrdenSvg('rol') !!}</a></th>
                <th><a href="{{ route('admin.users.index', ordenarPor('active')) }}">Activo {!! iconoOrdenSvg('active') !!}</a></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="{{ !$user->active ? 'opacity-60' : '' }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->surname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="codigo-container" data-user-id="{{ $user->id }}">
                            <span class="codigo-text">{{ $user->codigo }}</span>
                            <form action="{{ route('admin.users.updateCode', $user) }}" method="POST" class="hidden codigo-form items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="codigo" class="input input-sm input-bordered uppercase w-20 codigo-input" value="{{ $user->codigo }}" pattern="[A-Z]{2}[0-9]{2}" required>
                                <button type="button" class="btn btn-sm btn-success guardar-btn" disabled>Guardar</button>
                                <button type="button" class="btn btn-sm btn-ghost cancelar-btn">Cancelar</button>
                            </form>
                            <button type="button" class="btn btn-xs btn-outline editar-btn">Editar</button>
                        </div>
                    
                        {{-- Modal de confirmación --}}
                        <dialog id="modal-codigo-{{ $user->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg">¿Confirmar cambio de código?</h3>
                                <p class="py-4">¿Seguro que quieres guardar el nuevo código?</p>
                                <div class="modal-action">
                                    <form method="dialog" class="flex gap-2">
                                        <button type="submit" class="btn btn-primary confirmar-modal-btn" data-user-id="{{ $user->id }}">Sí, guardar</button>
                                        <button type="button" class="btn cancelar-modal-btn" data-user-id="{{ $user->id }}">Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </td>
                    <td>
                        <div class="rol-container" data-user-id="{{ $user->id }}">
                            <span class="rol-text">{{ ucfirst($user->rol) }}</span>
                            <form action="{{ route('admin.users.changeRole', $user) }}" method="POST" class="hidden rol-form items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="rol" class="select select-sm select-bordered w-28">
                                    <option value="admin" {{ $user->rol === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="profesor" {{ $user->rol === 'profesor' ? 'selected' : '' }}>Profesor</option>
                                    <option value="invitado" {{ $user->rol === 'invitado' ? 'selected' : '' }}>Invitado</option>
                                </select>
                                <button type="button" class="btn btn-sm btn-success guardar-rol-btn" disabled>Guardar</button>
                                <button type="button" class="btn btn-sm btn-ghost cancelar-rol-btn">Cancelar</button>
                            </form>
                            <button type="button" class="btn btn-xs btn-outline editar-rol-btn">Editar</button>
                        </div>
                    
                        {{-- Modal de confirmación para rol --}}
                        <dialog id="modal-rol-{{ $user->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg">¿Confirmar cambio de rol?</h3>
                                <p class="py-4">¿Seguro que quieres actualizar el rol de este usuario?</p>
                                <div class="modal-action">
                                    <form method="dialog" class="flex gap-2">
                                        <button type="submit" class="btn btn-primary confirmar-rol-modal-btn" data-user-id="{{ $user->id }}">Sí, guardar</button>
                                        <button type="button" class="btn cancelar-rol-modal-btn" data-user-id="{{ $user->id }}">Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </td>                    
                    <td>
                        <form action="{{ route('admin.users.toggleActive', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-warning' : 'btn-success' }}">
                                {{ $user->active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-error">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-6">
        {{ $users->links('vendor.pagination.daisy') }}
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Código
        document.querySelectorAll('.codigo-container').forEach(container => {
            const userId = container.dataset.userId;
            const textEl = container.querySelector('.codigo-text');
            const form = container.querySelector('.codigo-form');
            const input = container.querySelector('.codigo-input');
            const editarBtn = container.querySelector('.editar-btn');
            const guardarBtn = container.querySelector('.guardar-btn');
            const cancelarBtn = container.querySelector('.cancelar-btn');
            const modal = document.getElementById(`modal-codigo-${userId}`);
            const confirmarModalBtn = modal.querySelector('.confirmar-modal-btn');
            const cancelarModalBtn = modal.querySelector('.cancelar-modal-btn');

            let originalValue = input.value;
            guardarBtn.disabled = true;

            input.addEventListener('input', () => {
                guardarBtn.disabled = (input.value === originalValue);
            });

            editarBtn.addEventListener('click', () => {
                textEl.style.display = 'none';
                form.classList.remove('hidden');
                form.classList.add('flex');
                editarBtn.style.display = 'none';
            });

            cancelarBtn.addEventListener('click', () => {
                input.value = originalValue;
                guardarBtn.disabled = true;
                form.classList.remove('flex');
                form.classList.add('hidden');
                textEl.style.display = '';
                editarBtn.style.display = '';
            });

            guardarBtn.addEventListener('click', () => {
                modal.showModal();
            });

            confirmarModalBtn.addEventListener('click', () => {
                form.submit();
            });

            cancelarModalBtn.addEventListener('click', () => {
                modal.close();
            });
        });

        // Rol
        document.querySelectorAll('.rol-container').forEach(container => {
            const userId = container.dataset.userId;
            const textEl = container.querySelector('.rol-text');
            const form = container.querySelector('.rol-form');
            const select = form.querySelector('select[name="rol"]');
            const editarBtn = container.querySelector('.editar-rol-btn');
            const guardarBtn = container.querySelector('.guardar-rol-btn');
            const cancelarBtn = container.querySelector('.cancelar-rol-btn');
            const modal = document.getElementById(`modal-rol-${userId}`);
            const confirmarModalBtn = modal.querySelector('.confirmar-rol-modal-btn');
            const cancelarModalBtn = modal.querySelector('.cancelar-rol-modal-btn');

            let originalValue = select.value;
            guardarBtn.disabled = true;

            select.addEventListener('change', () => {
                guardarBtn.disabled = (select.value === originalValue);
            });

            editarBtn.addEventListener('click', () => {
                textEl.style.display = 'none';
                form.classList.remove('hidden');
                form.classList.add('flex');
                editarBtn.style.display = 'none';
            });

            cancelarBtn.addEventListener('click', () => {
                select.value = originalValue;
                guardarBtn.disabled = true;
                form.classList.remove('flex');
                form.classList.add('hidden');
                textEl.style.display = '';
                editarBtn.style.display = '';
            });

            guardarBtn.addEventListener('click', () => {
                modal.showModal();
            });

            confirmarModalBtn.addEventListener('click', () => {
                form.submit();
            });

            cancelarModalBtn.addEventListener('click', () => {
                modal.close();
            });
        });

        document.querySelectorAll('.close-alert').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.alert').remove();
            });
        });

    });
</script>  
@endsection
