@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-semibold text-center">Crear usuarios</h2>

    {{-- Alerta post-creación con enlace de descarga --}}
    @if (session('success'))
    <div class="alert alert-success shadow-lg">
        <div>
            <span>{{ session('success') }}</span>
            <a href="{{ route('admin.users.create.download') }}" class="link link-primary ml-2" download>Descargar CSV con claves</a>
        </div>
    </div>
    @endif

    @if (!isset($preview))
        {{-- Formulario individual --}}
        <div id="formulario-individual" class="bg-base-200 p-6 rounded-xl shadow">
            <h3 class="text-xl font-bold mb-4">Crear usuario individual</h3>
            <form action="{{ route('admin.users.create.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="codigo" placeholder="Código" class="input input-bordered w-full" required>
                    <input type="text" name="name" placeholder="Nombre" class="input input-bordered w-full" required>
                    <input type="text" name="surname" placeholder="Apellidos" class="input input-bordered w-full" required>
                    <input type="email" name="email" placeholder="Email" class="input input-bordered w-full" required>
                </div>
                <button type="submit" class="btn btn-primary">Crear usuario</button>
            </form>
        </div>
    @endif

    {{-- Formulario de carga masiva --}}
    <div class="bg-base-200 p-6 rounded-xl shadow">
        <h3 class="text-xl font-bold mb-4">Carga masiva de usuarios</h3>
        <form action="{{ route('admin.users.create.confirm') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input id="archivo" type="file" name="archivo" class="file-input file-input-bordered w-full" accept=".xls,.xlsx" required>
            <button type="submit" class="btn btn-secondary">Previsualizar</button>
        </form>
    </div>

    {{-- Previsualización (si se ha enviado un archivo) --}}
    @isset($preview)
        <div class="bg-base-300 p-6 rounded-xl shadow">
            <h3 class="text-xl font-bold mb-4">Previsualización de usuarios</h3>
            <form action="{{ route('admin.users.create.storeMassive') }}" method="POST">
                @csrf
                <input type="hidden" name="data_serialized" value="{{ base64_encode(serialize($preview)) }}">

                {{-- Responsive layout --}}
                <div class="space-y-4 md:hidden">
                    @foreach ($preview as $item)
                        <div class="border p-4 rounded-lg {{ $item['valid'] ? ($item['exists'] ?? false ? 'border-yellow-500 bg-yellow-100 text-yellow-800' : 'border-green-500 bg-green-100 text-green-800') : 'border-red-500 bg-red-100 text-red-800' }}">
                            <p><strong>Código:</strong> {{ $item['codigo'] ?? '-' }}</p>
                            <p><strong>Nombre:</strong> {{ $item['name'] }}</p>
                            <p><strong>Apellidos:</strong> {{ $item['surname'] }}</p>
                            <p><strong>Email:</strong> 
                                <span>
                                    {!! str_replace(['.', '@'], ['.<wbr>', '@<wbr>'], $item['email']) !!}
                                </span>
                            </p>
                            <p>
                                <strong>Estado:</strong>
                                @if(!$item['valid'])
                                    ❌ {{ $item['error'] }}
                                @elseif($item['exists'] ?? false)
                                    ⚠️ Ya existe
                                @else
                                    ✅ Nuevo
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Tabla visible solo en pantallas medianas y grandes --}}
                <div class="overflow-x-auto max-h-96 overflow-y-scroll hidden md:block">
                    <table class="table table-zebra w-full text-sm">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Email</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($preview as $item)
                                <tr class="{{ $item['valid'] ? ($item['exists'] ?? false ? 'text-warning' : '') : 'text-error' }}">
                                    <td>{{ $item['codigo'] ?? '-' }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['surname'] }}</td>
                                    <td>{!! str_replace(['.', '@'], ['.<wbr>', '@<wbr>'], $item['email']) !!}</td>
                                    <td>
                                        @if(!$item['valid'])
                                            ❌ {{ $item['error'] }}
                                        @elseif($item['exists'] ?? false)
                                            ⚠️ Ya existe
                                        @else
                                            ✅ Nuevo
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end items-center mt-6">
                    <button type="submit" class="btn btn-success" @if(!collect($preview)->contains('valid', true)) disabled @endif>
                        Guardar usuarios válidos
                    </button>
                </div>
            </form>
        </div>
    @endisset
@endsection
