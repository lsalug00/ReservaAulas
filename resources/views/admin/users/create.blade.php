@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-10 py-8">

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
            <div class="overflow-x-auto max-h-96 overflow-y-scroll">
                <table class="table table-zebra w-full text-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview as $item)
                            <tr class="{{ $item['valid'] ? '' : 'text-error' }}">
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['surname'] }}</td>
                                <td>{{ $item['email'] }}</td>
                                <td>
                                    @if($item['valid'])
                                        ✅
                                    @else
                                        ❌ {{ $item['error'] }}
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

</div>

<script>
    document.getElementById('archivo')?.addEventListener('change', function () {
        const individualForm = document.getElementById('formulario-individual');
        if (this.files.length > 0) {
            individualForm.style.display = 'none';
        } else {
            individualForm.style.display = '';
        }
    });
</script>
@endsection
