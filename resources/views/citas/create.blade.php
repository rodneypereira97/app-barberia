@extends('layouts.app')

@section('title', 'Registrar Cita')

@section('content_header')
    <h1 class="mb-4">Registrar Cita</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Formulario de Registro</h5>
            </div>
            <div class="card-body">
                <!-- Mostrar mensaje de error -->
                @push('scripts')
                    @if ($errors->any())
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                html: `{!! implode('<br>', $errors->all()) !!}`
                            });
                        </script>
                    @endif

                    @if(session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '{{ session('error') }}'
                            });
                        </script>
                    @endif
                @endpush


                <form id="formCita" action="{{ route('citas.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="cliente_id">Cliente</label>
                        <select name="cliente_id" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="servicio_id">Servicio</label>
                        <select name="servicio_id" class="form-control" required>
                            <option value="">Seleccione un servicio</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="user_id">Barbero</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Seleccione un barbero</option>
                            @foreach ($barberos as $barbero)
                                <option value="{{ $barbero->id }}">{{ $barbero->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                        <div id="errorFecha" style="color: red; font-weight: bold; display: none;"></div>

                    </div>

                    <div class="form-group mb-4">
                        <label for="hora">Hora</label>
                        <input type="time" id="hora" name="hora" class="form-control" required>
                        <div id="errorHora" style="color: red; font-weight: bold; display: none;"></div>
                        <div id="errorDisponibilidad" style="color: red; font-weight: bold; display: none;"></div>

                    </div>


                    <button type="submit" class="btn btn-success" id="btnGuardar">
                        <i class="fas fa-save"></i> Guardar Cita
                    </button>
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </form>

                <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const fechaInput = document.getElementById('fecha');
                            const horaInput = document.getElementById('hora');
                            const errorFechaDiv = document.getElementById('errorFecha');
                            const errorHoraDiv = document.getElementById('errorHora');
                            const btnGuardar = document.getElementById('btnGuardar');
                            const form = document.getElementById('formCita');

                            function validarFechaHora() {
                                const fecha = fechaInput.value;
                                const hora = horaInput.value;

                                // Ocultar errores inicialmente
                                errorFechaDiv.style.display = 'none';
                                errorHoraDiv.style.display = 'none';
                                btnGuardar.disabled = false;

                                if (!fecha || !hora) {
                                    return; // No validamos si no hay fecha o hora
                                }

                                const ahora = new Date();
                                const fechaSeleccionada = new Date(fecha + 'T00:00:00');
                                const fechaHoy = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate());

                                // Validar fecha
                                if (fechaSeleccionada < fechaHoy) {
                                    errorFechaDiv.textContent = 'La fecha no puede ser anterior al día de hoy.';
                                    errorFechaDiv.style.display = 'block';
                                    btnGuardar.disabled = true;
                                }

                                // Validar hora solo si la fecha es hoy
                                if (fechaSeleccionada.getTime() === fechaHoy.getTime()) {
                                    const [horaSel, minSel] = hora.split(':');
                                    const fechaHoraSeleccionada = new Date(fecha + 'T' + hora);
                                    if (fechaHoraSeleccionada < ahora) {
                                        errorHoraDiv.textContent = 'La hora no puede ser anterior a la hora actual.';
                                        errorHoraDiv.style.display = 'block';
                                        btnGuardar.disabled = true;
                                    }
                                }
                            }

                            function verificarDisponibilidadBarbero() {
                                const barberoId = document.querySelector('select[name="user_id"]').value;
                                const fecha = fechaInput.value;
                                const hora = horaInput.value;
                                const servicioId = document.querySelector('select[name="servicio_id"]').value;
                                const errorDisp = document.getElementById('errorDisponibilidad');

                                if (!barberoId || !fecha || !hora) {
                                    errorDisp.style.display = 'none';
                                    return;
                                }

                                fetch(`/verificar-cita?barbero_id=${barberoId}&fecha=${fecha}&hora=${hora}&servicio_id=${servicioId}`)
                                .then(response => response.json())
                                    .then(data => {
                                        if (!data.disponible) {
                                            errorDisp.textContent = `El barbero ${data.barbero} ya tiene una cita en esa fecha y hora.`;
                                            errorDisp.style.display = 'block';
                                            btnGuardar.disabled = true;
                                        } else {
                                            errorDisp.style.display = 'none';
                                            validarFechaHora(); // Vuelve a validar fecha/hora para evitar conflictos
                                        }
                                    });
                            }

                            fechaInput.addEventListener('change', () => {
                                validarFechaHora();
                                verificarDisponibilidadBarbero();
                            });
                            horaInput.addEventListener('change', () => {
                                validarFechaHora();
                                verificarDisponibilidadBarbero();
                            });
                            document.querySelector('select[name="user_id"]').addEventListener('change', () => {
                                validarFechaHora();
                                verificarDisponibilidadBarbero();
                            });
                            document.querySelector('select[name="servicio_id"]').addEventListener('change', () => {
                                validarFechaHora();
                                verificarDisponibilidadBarbero();
                            });



                            form.addEventListener('submit', function(e) {
                                validarFechaHora();
                                verificarDisponibilidadBarbero();

                                if (btnGuardar.disabled) {
                                    e.preventDefault();
                                }
                            });
                        });              

                </script>


            </div>
        </div>
    </div>
</div>
@stop
