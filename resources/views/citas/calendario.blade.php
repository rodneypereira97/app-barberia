@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Calendario de Citas</h1>
    <div id="calendar"></div>
    <div class="d-flex justify-content-center mt-4">
        <div class="legend-container" aria-label="Leyenda de estados">
    <div class="legend-item">
        <div class="legend-color legend-completada"></div> Completada
    </div>
    <div class="legend-item">
        <div class="legend-color legend-pendiente"></div> Pendiente
    </div>
    <div class="legend-item">
        <div class="legend-color legend-anulada"></div> Anulada
    </div>
</div>

    </div>


</div>
@endsection

@push('styles')
<style>
    #calendar {
    max-width: 1100px;
    margin: 20px auto;
    min-height: 600px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    padding: 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.legend-container {
    max-width: 1100px;
    margin: 0 auto 20px;
    background-color: #f8f9fa;
    padding: 12px 25px;
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    display: flex;
    justify-content: center;
    gap: 25px;
    font-size: 14px;
    color: #495057;
    font-weight: 600;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 18px;
    height: 18px;
    border-radius: 4px;
}

/* Colores de la leyenda */
.legend-completada { background-color: #28a745; } /* verde */
.legend-pendiente { background-color: #ffc107; } /* amarillo */
.legend-anulada   { background-color: #dc3545; } /* rojo */

/* Estilos para los eventos */
.fc-daygrid-event {
    border-radius: 8px !important;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    padding: 4px 8px !important;
    line-height: 1.2 !important;
}

.fc-daygrid-event.completada {
    background-color: #28a745 !important;
    color: white !important;
    text-decoration: line-through;
    opacity: 0.85;
}

.fc-daygrid-event.pendiente {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.fc-daygrid-event.anulada {
    background-color: #dc3545 !important;
    color: white !important;
}


/* Ajustes de fuente y tamaño */
.fc .fc-daygrid-day-number {
    font-weight: 600;
    font-size: 14px;
    color: #495057;
}
.fc-daygrid-event-dot {
    display: none !important;
}


.fc .fc-toolbar-title {
    font-weight: 700;
    font-size: 24px;
    color: #343a40;
}

.fc .fc-col-header-cell-cushion {
    font-weight: 600;
    color: #495057;
    font-size: 15px;
}

/* Cursor pointer en eventos */
.fc-daygrid-event:hover {
    cursor: pointer;
    filter: brightness(0.9);
}

</style>
@endpush

@push('scripts')
<!-- Bootstrap Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 'auto',
        themeSystem: 'bootstrap',
        events: {!! $citas->map(function($cita) {
            return [
                'title' => $cita->cliente->nombre . ' con ' . $cita->user->name,
                'start' => $cita->fecha . 'T' . $cita->hora,
                'classNames' => [$cita->estado], // clase para colorear por estado
                'extendedProps' => [
                    'servicio' => $cita->servicio->nombre,
                    'barbero' => $cita->user->name,
                    'cliente' => $cita->cliente->nombre,
                    'estado' => $cita->estado,
                ],
            ];
        })->toJson() !!},
        eventDidMount: function(info) {
            new bootstrap.Tooltip(info.el, {
                title: `${info.event.extendedProps.servicio} - ${info.event.extendedProps.barbero} - Estado: ${info.event.extendedProps.estado}`,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        }
    });
    calendar.render();
});

</script>
@endpush


