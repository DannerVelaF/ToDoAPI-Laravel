<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TareaController extends Controller
{

    public function me()
    {
        $user = Auth::user();
        $user->load('Tareas');
        return response()->json($user);
    }

    public function index()
    {
        $user = Auth::user();
        $tareas = Tarea::where('user_id', $user->id)->get();
        return response()->json($tareas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tarea = Tarea::create([
            'Titulo' => $request->Titulo,
            'Descripcion' => $request->Descripcion,
            'Completada' => false,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Tarea creada correctamente',
            'tarea' => $tarea
        ]);
    }

    public function show($id)
    {
        $tarea = Tarea::find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada, no se puede mostrar'], 404);
        }

        return response()->json($tarea);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Titulo' => 'required|string|max:255',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $tarea = Tarea::find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada, no se puede actualizar'], 404);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tarea->update([
            'Titulo' => $request->Titulo,
            'Descripcion' => $request->Descripcion,
        ]);
        return response()->json([
            'message' => 'Tarea actualizada correctamente',
            'tarea' => $tarea
        ]);
    }

    public function destroy($id)
    {
        $tarea = Tarea::find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada, no se puede eliminar'], 404);
        }

        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada correctamente']);
    }

    public function completeTask(Request $request, $id)
    {

        $user = Auth::user();
        $tarea = Tarea::where('user_id', $user->id)->find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada, no se puede completar'], 404);
        }
        $tarea->update(['Completada' => $request->Completada]);
        $tarea->save();
        return response()->json(['message' => 'Tarea completada correctamente', 'tarea' => $tarea]);
    }

    public function multipleCompleteTask(Request $request)
    {
        $tareas = Tarea::whereIn('id', $request->ids)->where('user_id', Auth::id())->get();

        foreach ($tareas as $tarea) {
            $tarea->update(['Completada' => $request->Completada]);
        }

        return response()->json(['message' => 'Tareas completadas correctamente']);
    }

    public function searchByTitle(Request $request)
    {

        $tareas = Tarea::where('user_id', Auth::id())->where('Titulo', 'like', '%' . $request->search . '%')->get();
        if (!$tareas) {
            return response()->json(['message' => 'No hay tareas que coincidan con el criterio de búsqueda'], 404);
        }

        return response()->json($tareas);
    }

    public function filterCompleteTask()
    {
        $tareas = Tarea::where('user_id', Auth::id())->where('Completada', true)->get();
        if (!$tareas) {
            return response()->json(['message' => 'No hay tareas que completar'], 404);
        }

        foreach ($tareas as $tarea) {
            $tarea->update(['Completada' => true]);
        }
        return response()->json($tareas);
    }
}
