@extends('layouts.admin')

@section('title', 'Importar días no lectivos')

@section('page-id', 'dias-no-lectivos')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-8">
    <h1 class="text-2xl font-bold">Importar días no lectivos</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif

    <form action="{{ route('admin.dias-no-lectivos.import') }}" method="POST" enctype="multipart/form-data"
          class="space-y-4 w-full sm:max-w-md">
        @csrf
        <div>
            <label class="label">Archivo PDF del calendario escolar:</label>
            <input type="file" name="pdf" accept=".pdf" class="file-input file-input-bordered w-full" required>
        </div>
        <button class="btn btn-primary" type="submit">Importar</button>
    </form>

   @if(isset($eventos))
        <div class="mt-6 space-y-2">
            <h3 class="text-sm font-semibold">Leyenda:</h3>
            <div class="flex flex-wrap gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-error/80"></div> Importado desde PDF
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-success/80"></div> Añadido manualmente
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-warning/80"></div> Fin de semana (no se guardará)
                </div>
            </div>
        </div>

        <button id="btn-guardar-todos" class="btn btn-success mt-6 w-full sm:w-auto">Guardar días no lectivos</button>

        <form id="form-guardar-dias" action="{{ route('admin.dias-no-lectivos.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="eventos" id="input-eventos">
        </form>    

        <div id="calendario" class="mt-10">
            <h2 class="text-xl font-semibold mb-4">Previsualización de días no lectivos</h2>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <button id="prev" class="btn btn-sm btn-outline">Anterior</button>
                <h3 id="mes-actual" class="text-lg font-bold text-center flex-1"></h3>
                <button id="next" class="btn btn-sm btn-outline">Siguiente</button>
            </div>

            <div id="calendar-grid"
                 class="grid grid-cols-7 gap-2 text-center text-sm sm:text-base"
                 style="word-break: break-word;">
            </div>
        </div>

        <div id="descripcion-dia" class="mt-4 p-4 bg-base-200 rounded hidden"></div>

        {{-- Modal para agregar día --}}
        <dialog id="modal-agregar" class="modal">
            <div class="modal-box">
              <h3 class="font-bold text-lg">Agregar día no lectivo</h3>
              <p class="py-2" id="modal-fecha"></p>
              <input id="modal-descripcion" type="text" placeholder="Descripción"
                     class="input input-bordered w-full mb-4" />
              <div class="modal-action flex justify-end gap-2">
                <button class="btn" id="btn-cancelar-dia">Cancelar</button>
                <button class="btn btn-primary" id="btn-guardar-dia">Guardar</button>
              </div>
            </div>
        </dialog>

        @push('scripts')
            <script>
                window.eventosData = @json(json_decode($eventos) ?? []);
                window.fechaInicio = "{{ $startDate }}";
                window.fechaFin = "{{ $endDate }}";
            </script>
        @endpush          
    @endif
</div>
@endsection

