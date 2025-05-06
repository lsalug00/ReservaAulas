@extends('layouts.admin')

@section('title', 'Importar días no lectivos')

@section('content')
<div class="max-w-4xl mx-auto py-8 space-y-8">
    <h1 class="text-2xl font-bold">📆 Importar días no lectivos</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    <form action="{{ route('admin.dias-no-lectivos.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-md">
        @csrf
        <div>
            <label class="label">Archivo PDF del calendario escolar:</label>
            <input type="file" name="pdf" accept=".pdf" class="file-input file-input-bordered w-full" required>
        </div>
        <button class="btn btn-primary" type="submit">📤 Importar</button>
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
        <button id="btn-guardar-todos" class="btn btn-success mt-6">💾 Guardar días no lectivos</button>

        <form id="form-guardar-dias" action="{{ route('admin.dias-no-lectivos.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="eventos" id="input-eventos">
        </form>    

        <div id="calendario" class="mt-10">
            <h2 class="text-xl font-semibold mb-4">🗓️ Previsualización de días no lectivos</h2>
            <div class="flex items-center justify-between mb-4">
                <button id="prev" class="btn btn-sm btn-outline">⬅️ Anterior</button>
                <h3 id="mes-actual" class="text-lg font-bold"></h3>
                <button id="next" class="btn btn-sm btn-outline">Siguiente ➡️</button>
            </div>
            <div id="calendar-grid" class="grid grid-cols-7 gap-2 text-center text-sm"></div>
        </div>
        <div id="descripcion-dia" class="mt-4 p-4 bg-base-200 rounded hidden"></div>

        <dialog id="modal-agregar" class="modal">
            <div class="modal-box">
              <h3 class="font-bold text-lg">Agregar día no lectivo</h3>
              <p class="py-2" id="modal-fecha"></p>
              <input id="modal-descripcion" type="text" placeholder="Descripción" class="input input-bordered w-full mb-4" />
              <div class="modal-action">
                <button class="btn" id="btn-cancelar-dia">Cancelar</button>
                <button class="btn btn-primary" id="btn-guardar-dia">Guardar</button>
              </div>
            </div>
        </dialog>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const eventos = @json(json_decode($eventos));
                const diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
            
                const fechaInicio = new Date("{{ $startDate }}"); // septiembre
                const fechaFin = new Date("{{ $endDate }}");     // junio siguiente
                let fechaReferencia = new Date("{{ $startDate }}"); // arranca en septiembre
                let fechaSeleccionada = null;
            
                const container = document.getElementById('calendar-grid');
                const mesActual = document.getElementById('mes-actual');
                const btnPrev = document.getElementById('prev');
                const btnNext = document.getElementById('next');
                const modal = document.getElementById('modal-agregar');
                const modalFecha = document.getElementById('modal-fecha');
                const modalDescripcion = document.getElementById('modal-descripcion');
                const btnGuardar = document.getElementById('btn-guardar-dia');
                const btnCancelar = document.getElementById('btn-cancelar-dia');

                const render = () => {
                    container.innerHTML = '';
                    mesActual.textContent = fechaReferencia.toLocaleDateString('es-ES', {
                        month: 'long',
                        year: 'numeric'
                    });
            
                    diasSemana.forEach(dia => {
                        const cell = document.createElement('div');
                        cell.className = 'font-bold';
                        cell.textContent = dia;
                        container.appendChild(cell);
                    });
            
                    const year = fechaReferencia.getFullYear();
                    const month = fechaReferencia.getMonth();
                    const primerDiaMes = new Date(year, month, 1);
            
                    let inicio = primerDiaMes.getDay();
                    inicio = inicio === 0 ? 6 : inicio - 1; // lunes como primer día
            
                    for (let i = 0; i < inicio; i++) {
                        container.appendChild(document.createElement('div'));
                    }
            
                    const diasEnMes = new Date(year, month + 1, 0).getDate();
            
                    for (let dia = 1; dia <= diasEnMes; dia++) {
                        const fechaActual = new Date(year, month, dia);
                        const fechaISO = fechaActual.getFullYear() + '-' +
                            String(fechaActual.getMonth() + 1).padStart(2, '0') + '-' +
                            String(fechaActual.getDate()).padStart(2, '0'); // YYYY-MM-DD
                        
                        const diaSemana = fechaActual.getDay(); // 0 (Dom) - 6 (Sáb)
                        const esFinde = diaSemana === 0 || diaSemana === 6;
            
                        const evento = eventos.find(e => e.start === fechaISO);
            
                        const cell = document.createElement('div');
                        cell.className = 'p-2 rounded relative text-center';
            
                        if (evento) {
                            if (evento.esManual) {
                                cell.classList.add('bg-success/80', 'hover:brightness-90');
                            } else if (evento.es_finde) {
                                cell.classList.add('bg-warning/80', 'hover:brightness-90');
                            } else {
                                cell.classList.add('bg-error/80', 'hover:brightness-90');
                            }
                            cell.addEventListener('click', () => {
                                const contenedor = document.getElementById('descripcion-dia');
                                const fechaFormateada = fechaActual.toLocaleDateString('es-ES');
                                contenedor.textContent = `${fechaFormateada}: ${evento.title}`;
                                contenedor.classList.remove('hidden');
                            });
                        } else {
                            if (!esFinde) {
                                cell.classList.add('hover:bg-primary/50');
                                cell.addEventListener('click', () => {
                                    fechaSeleccionada = fechaISO;
                                    modalFecha.textContent = `Fecha: ${fechaActual.toLocaleDateString('es-ES')}`;
                                    modalDescripcion.value = '';
                                    modal.showModal();
                                });
                            } else {
                                cell.classList.add('text-gray-400');
                            }
                        }
            
                        cell.textContent = dia;
                        container.appendChild(cell);
                    }
            
                    // Deshabilitar navegación fuera del rango septiembre-junio
                    btnPrev.disabled = (
                        fechaReferencia.getFullYear() === fechaInicio.getFullYear() &&
                        fechaReferencia.getMonth() === fechaInicio.getMonth()
                    );
                    btnNext.disabled = (
                        fechaReferencia.getFullYear() === fechaFin.getFullYear() &&
                        fechaReferencia.getMonth() === fechaFin.getMonth()
                    );
                };
            
                btnPrev.addEventListener('click', () => {
                    fechaReferencia.setMonth(fechaReferencia.getMonth() - 1);
                    render();
                });
            
                btnNext.addEventListener('click', () => {
                    fechaReferencia.setMonth(fechaReferencia.getMonth() + 1);
                    render();
                });

                btnCancelar.addEventListener('click', () => {
                    modal.close();
                });

                btnGuardar.addEventListener('click', () => {
                    const descripcion = modalDescripcion.value.trim();
                    if (!descripcion) return;

                    eventos.push({
                        start: fechaSeleccionada,
                        title: descripcion,
                        es_finde: false,
                        esManual: true
                    });

                    modal.close();
                    render();
                });

                document.getElementById('btn-guardar-todos').addEventListener('click', () => {
                    const input = document.getElementById('input-eventos');
                    input.value = JSON.stringify(eventos);
                    document.getElementById('form-guardar-dias').submit();
                });
            
                render();
            });
        </script>            
    @endif
</div>
@endsection

