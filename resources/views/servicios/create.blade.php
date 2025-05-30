@extends('layouts.app')
@section('title', 'Nuevo Servicio')

@section('content_header')
    <h1>Registrar Nuevo Servicio</h1>
@stop

@section('content')
    <form action="{{ route('servicios.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio (S/.)</label>
            <input type="number" name="precio" step="0.01" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="duracion">Duración (en minutos)</label>
            <input type="number" name="duracion" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Servicio</button>
    </form>
@stop
