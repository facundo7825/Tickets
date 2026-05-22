<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        return view('tickets.index');
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function show($id)
    {
        return view('tickets.show');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'        => ['required', 'string', 'max:150'],
            'categoria'     => ['required', 'in:soporte,desarrollo,redes'],
            'subcategoria'  => ['required', 'string', 'max:100'],
            'prioridad'     => ['required', 'in:baja,media,alta,critica'],
            'justificacion' => ['required_if:prioridad,critica', 'nullable', 'string', 'min:20'],
            'descripcion'   => ['required', 'string', 'min:20'],
            'adjuntos'      => ['nullable', 'array', 'max:20'],
            'adjuntos.*'    => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,gif,webp,docx,xlsx,zip,txt,csv'],
        ], [
            'titulo.required'             => 'El título es obligatorio.',
            'titulo.max'                  => 'El título no puede superar los 150 caracteres.',
            'categoria.required'          => 'Seleccioná una categoría.',
            'subcategoria.required'       => 'Seleccioná una subcategoría.',
            'prioridad.required'          => 'Seleccioná una prioridad.',
            'justificacion.required_if'   => 'La justificación es obligatoria para prioridad crítica.',
            'justificacion.min'           => 'La justificación debe tener al menos 20 caracteres.',
            'descripcion.required'        => 'La descripción es obligatoria.',
            'descripcion.min'             => 'La descripción debe tener al menos 20 caracteres.',
            'adjuntos.*.max'              => 'Cada adjunto no puede superar los 10 MB.',
            'adjuntos.*.mimes'            => 'Tipo de archivo no permitido.',
            'adjuntos.max'                => 'Se permiten hasta 20 adjuntos por ticket.',
        ]);

        // TODO: persistir ticket, adjuntos y evento de creación
        return redirect()->route('dashboard')->with('success', 'Ticket enviado correctamente. En breve será asignado al equipo correspondiente.');
    }
}
