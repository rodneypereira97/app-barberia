<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;



class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $citas = \App\Models\Cita::with(['cliente', 'servicio', 'user'])
            ->when($buscar, function ($query, $buscar) {
                $query->whereHas('cliente', function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%$buscar%");
                })->orWhereHas('user', function ($q) use ($buscar) {
                    $q->where('name', 'like', "%$buscar%");
                })->orWhereHas('servicio', function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%$buscar%");
                });
            })
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        return view('citas.index', compact('citas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $barberos = User::all();
        $citas = Cita::all(); // Traemos todas las citas para mostrar ocupadas en el calendario
        return view('citas.create', compact('clientes', 'servicios', 'barberos'));
    }

    // Guardar la cita
    public function store(Request $request)
    {
        // Validación básica de los campos
        $request->validate([
            'cliente_id' => 'required',
            'servicio_id' => 'required',
            'user_id'     => 'required',
            'fecha'       => 'required|date',
            'hora'        => 'required|date_format:H:i',
        ]);

    
        // Crear la cita
        Cita::create([
            'cliente_id'  => $request->cliente_id,
            'servicio_id' => $request->servicio_id,
            'user_id'     => $request->user_id,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
        ]);
    
        return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function show(Cita $cita)
    {
        //
    }

    public function confirmar(Request $request, $id)
{
    $cita = Cita::findOrFail($id);
    $cita->estado = $request->estado ?? 'confirmada'; // Por si acaso no llega
    $cita->save();

    return redirect()->route('citas.index')->with('success', 'Cita confirmada correctamente.');
}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    
        $cita = Cita::findOrFail($id);
        $clientes = Cliente::all(); // si necesitas mostrar cliente a elegir
        $servicios = Servicio::all(); // si se puede cambiar el servicio
        $barberos = User::all();
        if ($cita->estado === 'completada') {
        return redirect()->route('citas.index')->with('error', 'No se puede editar una cita completada.');
    }


        return view('citas.edit', compact('cita', 'clientes', 'servicios', 'barberos'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
        'cliente_id' => 'required',
        'servicio_id' => 'required',
        'user_id'     => 'required',
        'fecha'       => 'required|date',
        'hora'        => 'required|date_format:H:i',
    ]);

        $cita = Cita::findOrFail($id);
        $cita->update([
        'cliente_id' => $request->cliente_id,
        'servicio_id' => $request->servicio_id,
        'user_id' => $request->user_id,        
        'fecha' => $request->fecha,
        'hora' => $request->hora,
        
    ]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }


    public function calendario()
    {
        $citas = Cita::with(['cliente', 'servicio', 'user'])->get();

        return view('citas.calendario', compact('citas'));
    }
    

    public function verificarDisponibilidad(Request $request)
    {
        try {
            $barberoId = $request->input('barbero_id');
            $fecha = $request->input('fecha');
            $hora = $request->input('hora');
            $servicioId = $request->input('servicio_id');

            if (!$barberoId || !$fecha || !$hora || !$servicioId) {
                return response()->json(['disponible' => true]);
            }

            $servicio = Servicio::find($servicioId);
            if (!$servicio) {
                return response()->json(['disponible' => true]);
            }

            $barbero = User::find($barberoId);

            $inicioNueva = Carbon::parse("$fecha $hora");
            $finNueva = (clone $inicioNueva)->addMinutes($servicio->duracion);

            $citas = Cita::where('user_id', $barberoId)
                ->where('fecha', $fecha)
                ->with('servicio')
                ->get();

            foreach ($citas as $cita) {
                if (!$cita->servicio) continue; // evitar errores si la relación no está

                $inicioExistente = Carbon::parse($cita->fecha . ' ' . $cita->hora);
                $finExistente = (clone $inicioExistente)->addMinutes($cita->servicio->duracion ?? 60);

                if ($inicioNueva < $finExistente && $finNueva > $inicioExistente) {
                    return response()->json([
                        'disponible' => false,
                        'barbero' => $barbero ? $barbero->name : 'Desconocido'

                    ]);
                }
            }

            return response()->json(['disponible' => true]);
        } catch (\Exception $e) {
            // Loguear el error para depuración
            \Log::error('Error en verificarDisponibilidad: ' . $e->getMessage());
            return response()->json(['disponible' => true], 500);
        }
    }
}