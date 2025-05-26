@extends('layouts.admin')

@section('title', 'Gestion de Aulas')

@section('page-id', 'aulas')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Gestión de Aulas Existentes</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.aulas.manage') }}" id="filtroForm"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 w-full">
        @csrf

        <input type="hidden" name="orden" value="{{ request('orden') }}">
        <input type="hidden" name="direccion" value="{{ request('direccion', 'asc') }}">

        {{-- Capacidad mínima --}}
        <div class="form-control">
            <label class="label">Capacidad mínima</label>
            <input type="number" id="capacidad" name="capacidad" class="input input-bordered w-full"
                value="{{ $request->input('capacidad')}}" placeholder="Ej: 20" min="1">
        </div>

        {{-- Categoría --}}
        <div class="form-control">
            <label class="label">Categoría</label>
            <select name="categoria_id" class="select select-bordered w-full">
                <option value="">Todas</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $request->input('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ ucfirst($categoria->nombre) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Edificio --}}
        <div class="form-control">
            <label class="label">Edificio</label>
            <select name="edificio" class="select select-bordered w-full">
                <option value="">Todos</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio }}" {{ $request->input('edificio') == $edificio ? 'selected' : '' }}>
                        {{ ucfirst($edificio) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Planta --}}
        <div class="form-control">
            <label class="label">Planta</label>
            <select name="planta" class="select select-bordered w-full">
                <option value="">Todas</option>
                @foreach ($plantas as $planta)
                    <option value="{{ $planta }}" {{ $request->input('planta') == $planta ? 'selected' : '' }}>
                        {{ $planta }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Acciones --}}
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 flex flex-wrap justify-end gap-2 items-end mt-2">
            <button type="submit" class="btn btn-primary w-full sm:w-auto">Filtrar</button>

            <a href="{{ route('admin.aulas.manage', ['orden' => request('orden'), 'direccion' => request('direccion')]) }}"
            class="btn btn-outline w-full sm:w-auto">Limpiar filtros</a>

            <a href="{{ route('admin.aulas.manage', request()->except(['orden', 'direccion'])) }}"
            class="btn btn-outline w-full sm:w-auto">Limpiar orden</a>
        </div>
    </form>

    @php
        $ordenActual = request('orden');
        $direccionActual = request('direccion', 'asc');

        function ordenarPor($columna) {
            $direccion = request('orden') === $columna && request('direccion') === 'asc' ? 'desc' : 'asc';
            return array_merge(request()->all(), ['orden' => $columna, 'direccion' => $direccion]);
        }

        function iconoOrdenSvg($columna) {
            if (request('orden') !== $columna) return '';

            return request('direccion') === 'asc'
                ? '<svg class="inline w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 mb-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 6l-5 6h10l-5-6z" /></svg>'
                : '<svg class="inline w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 mb-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M10 14l5-6H5l5 6z" /></svg>';
        }
    @endphp

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('admin.aulas.manage', ordenarPor('codigo')) }}">Código {!! iconoOrdenSvg('codigo') !!}</a>
                    </th>
                    <th>
                        <a href="{{ route('admin.aulas.manage', ordenarPor('nombre')) }}">Nombre {!! iconoOrdenSvg('nombre') !!}</a>
                    </th>
                    <th class="hidden sm:table-cell">
                        <a href="{{ route('admin.aulas.manage', ordenarPor('capacidad')) }}">Capacidad {!! iconoOrdenSvg('capacidad') !!}</a>
                    </th>
                    <th class="hidden md:table-cell">
                        <a href="{{ route('admin.aulas.manage', ordenarPor('categoria_id')) }}">Categoría {!! iconoOrdenSvg('categoria_id') !!}</a>
                    </th>
                    <th class="hidden md:table-cell">
                        <a href="{{ route('admin.aulas.manage', ordenarPor('edificio')) }}">Edificio {!! iconoOrdenSvg('edificio') !!}</a>
                    </th>
                    <th class="hidden md:table-cell">
                        <a href="{{ route('admin.aulas.manage', ordenarPor('planta')) }}">Planta {!! iconoOrdenSvg('planta') !!}</a>
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aulas as $aula)
                    <tr>
                        <td>{{ $aula->codigo }}</td>
                        <td>{{ $aula->nombre }}</td>
                        <td class="hidden sm:table-cell">{{ $aula->capacidad }}</td>
                        <td class="hidden md:table-cell">{{ $aula->categoria->nombre ?? 'N/A' }}</td>
                        <td class="hidden md:table-cell">{{ $aula->edificio }}</td>
                        <td class="hidden md:table-cell">{{ $aula->planta }}</td>
                        <td class="flex flex-col gap-1 md:flex-row md:gap-2">
                            <a href="{{ route('admin.aulas.edit', $aula) }}" class="btn btn-sm btn-info">Editar</a>
                            <!-- Botón para abrir modal -->
                            <button 
                                class="btn btn-sm btn-error btn-eliminar" 
                                data-id="{{ $aula->id }}"
                                data-nombre="{{ $aula->nombre }}"
                                type="button"
                            >
                                Eliminar
                            </button>
                            <!-- Formulario oculto para enviar la petición -->
                            <form id="delete-form-{{ $aula->id }}" action="{{ route('admin.aulas.destroy', $aula) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay aulas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $aulas->links('vendor.pagination.daisy') }}
    </div>

    <!-- Modal de confirmación -->
    <div id="delete-modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirmar eliminación</h3>
            <p class="py-4">¿Seguro que quieres eliminar el aula <span id="modal-aula-nombre" class="font-semibold"></span>?</p>
            <div class="modal-action">
            <button type="button" class="btn" id="btn-cancelar">Cancelar</button>
            <button type="button" class="btn btn-error" id="btn-eliminar-confirmar">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection
