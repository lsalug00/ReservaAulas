@extends('layouts.admin')

@section('title', 'Edición del aula {{ $aula->nombre }}')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar Aula: {{ $aula->nombre }}</h1>

    <form action="{{ route('admin.aulas.update', $aula) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Contenedor responsive en grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Código --}}
            <div>
                <label for="codigo" class="block font-medium mb-1">Código</label>
                <input type="text" name="codigo" id="codigo"
                    class="input input-bordered w-full @error('codigo') input-error @enderror"
                    value="{{ old('codigo', $aula->codigo) }}">
                @error('codigo')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nombre --}}
            <div>
                <label for="nombre" class="block font-medium mb-1">Nombre</label>
                <input type="text" name="nombre" id="nombre"
                    class="input input-bordered w-full @error('nombre') input-error @enderror"
                    value="{{ old('nombre', $aula->nombre) }}">
                @error('nombre')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Capacidad --}}
            <div>
                <label for="capacidad" class="block font-medium mb-1">Capacidad</label>
                <input type="number" name="capacidad" id="capacidad"
                    class="input input-bordered w-full @error('capacidad') input-error @enderror"
                    value="{{ old('capacidad', $aula->capacidad) }}" min="1">
                @error('capacidad')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoría --}}
            <div>
                <label for="categoria_id" class="block font-medium mb-1">Categoría</label>
                <select name="categoria_id" id="categoria_id"
                    class="select select-bordered w-full @error('categoria_id') select-error @enderror">
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected(old('categoria_id', $aula->categoria_id) == $categoria->id)>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Edificio --}}
            <div>
                <label for="edificio" class="block font-medium mb-1">Edificio</label>
                <input type="text" name="edificio" id="edificio"
                    class="input input-bordered w-full @error('edificio') input-error @enderror"
                    value="{{ old('edificio', $aula->edificio) }}">
                @error('edificio')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Planta --}}
            <div>
                <label for="planta" class="block font-medium mb-1">Planta</label>
                <input type="text" name="planta" id="planta"
                    class="input input-bordered w-full @error('planta') input-error @enderror"
                    value="{{ old('planta', $aula->planta) }}">
                @error('planta')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <button type="submit" class="btn btn-success w-full sm:w-auto">Actualizar Aula</button>
            <a href="{{ route('admin.aulas.manage') }}" class="btn btn-secondary w-full sm:w-auto">Cancelar</a>
        </div>
    </form>
</div>
@endsection