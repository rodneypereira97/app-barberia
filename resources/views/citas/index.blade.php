@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Citas</h1>

    @if(session('success'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarAlerta('success', '{{ session('success') }}');
            });
        </script>
        @endpush
    @endif

    <!-- Filtros simples -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('citas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Cita
        </a>

        <form method="GET" action="{{ route('citas.index') }}" class="d-flex" style="max-width: 500px;">
            <input type="text" name="buscar" class="form-control me-4" placeholder="Buscar por cliente, barbero o servicio..." value="{{ request('buscar') }}">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Barbero</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    <th>Calendario</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($citas as $cita)
                    <tr>
                        <td>{{ $cita->cliente->nombre }}</td>
                        <td>{{ $cita->servicio->nombre }}</td>
                        <td>{{ $cita->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                        <td>
                            <span class="badge 
                                {{ $cita->estado == 'pendiente' ? 'bg-warning' : 
                                ($cita->estado == 'completada' ? 'bg-success' : 'bg-danger') }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                        <td>
                            @if($cita->estado == 'pendiente')
                                <form action="{{ route('citas.confirmar', $cita->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="completada">
                                    <button type="submit" class="btn btn-success btn-sm" title="Confirmar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                          

                            <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="eliminar-servicio d-inline" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('citas.calendario') }}" class="btn btn-info mb-3">
                                <i class="fas fa-calendar-alt"></i> Calendario 
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay citas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
       

    </div>
</div>
@endsection
