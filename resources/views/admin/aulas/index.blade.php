@extends('layouts.admin')

@section('title', 'Creacion de Aulas')

@section('content')
<div class="max-w-4xl mx-auto py-8 space-y-10">
    <h1 class="text-2xl font-bold">Creacion de Aulas y Categorías</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif

    {{-- Crear Categoría --}}
    <form action="{{ route('admin.aulas.categoria.store') }}" method="POST" class="space-y-4">
        @csrf
        <h2 class="text-lg font-semibold">Nueva Categoría</h2>
        <div class="flex gap-2">
            <input type="text" name="nombre" placeholder="Nombre de la categoría" class="input input-bordered w-full" required>
            <button class="btn btn-primary">Crear</button>
        </div>
    </form>

    {{-- Crear Aula --}}
    <form action="{{ route('admin.aulas.store') }}" method="POST" class="space-y-4">
        @csrf
        <h2 class="text-lg font-semibold">Nueva Aula</h2>

        <div class="grid grid-cols-2 gap-4">
            <input name="codigo" type="text" placeholder="Código" class="input input-bordered w-full" required>
            <input name="nombre" type="text" placeholder="Nombre del aula" class="input input-bordered w-full" required>
            <input name="capacidad" type="number" placeholder="Capacidad" class="input input-bordered w-full" required min="1">
            <select name="categoria_id" class="select select-bordered w-full" required>
                <option disabled selected>Seleccionar categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
            <input name="edificio" type="text" placeholder="Edificio" class="input input-bordered w-full" required>
            <input name="planta" type="text" placeholder="Planta" class="input input-bordered w-full" required>
        </div>
        <button class="btn btn-success mt-2">Guardar Aula</button>
    </form>
</div>
@endsection
