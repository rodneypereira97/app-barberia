@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agregar Cliente</h1>
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Guardar Cliente</button>
        </form>
    </div>
@endsection
