@extends('layouts.app')
@section('title', 'Servicios')

@section('content')
<div class="container">
    <h1>Listado de Servicios</h1>
    <a href="{{ route('servicios.create') }}" class="btn btn-primary mb-3"><i class= "fas fa-plus"> </i> Nuevo Servicio</a>

    @if(session('success'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                mostrarAlerta('success', '{{ session('success') }}');
            });
        </script>
        @endpush
    @endif

    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Duración (min)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->id }}</td>
                        <td>{{ $servicio->nombre }}</td>
                        <td>Gs. {{ number_format($servicio->precio, 2) }}</td>
                        <td>{{ $servicio->duracion }}</td>
                        <td>
                            <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-sm btn-warning"><i class= "fas fa-pencil"> </i> Editar</a>
                            <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="eliminar-servicio d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class= "fas fa-trash"> </i> Eliminar</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
