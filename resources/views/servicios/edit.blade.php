@extends('layouts.app')
@section('title', 'Editar Servicio')

@section('content_header')
    <h1>Editar Servicio</h1>
@stop

@section('content')
    <form action="{{ route('servicios.update', $servicio) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" value="{{ $servicio->nombre }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio (S/.)</label>
            <input type="number" name="precio" step="0.01" value="{{ $servicio->precio }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="duracion">Duración (en minutos)</label>
            <input type="number" name="duracion" value="{{ $servicio->duracion }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Servicio</button>
    </form>
@stop
