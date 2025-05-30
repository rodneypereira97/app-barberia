@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class= "fas fa-plus"> </i> Agregar Cliente</a>
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
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-warning"><i class= "fas fa-pencil"> </i> Editar</a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="eliminar-servicio d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>  
    </div>
@endsection
