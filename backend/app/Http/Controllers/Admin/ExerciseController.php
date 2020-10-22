<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exercise;
use DB;


class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $ejercicios = Exercise::all();
            return $ejercicios;
        } else {
            return view('admin.exercise');
        }
    }

    public function store(Request $request)
    {
        $exercise = new Exercise();
        $exercise->cod = $request->cod;
        $exercise->name = $request->name;
        $exercise->pro = $request->pro;
        $exercise->save();

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $exercise->id . ".mp4";
            $file->move(public_path() . '/videos/' . $exercise->cod, $name);
            $exercise->url = $name;
        }
        $exercise->save();
        return "Success";
    }

    public function update(Request $request, $id)
    {
        $exercise = Exercise::find($id);
        $exercise->name = $request->name;
        $exercise->pro = $request->pro;
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $request->ejercicio_id . ".mp4";
            $file->move(public_path() . '/videos/' . $exercise->cod, $name);
            $exercise->url = $name;
        }
        $exercise->save();
        return "Success";
    }

    public function destroy($id)
    {
        $exercise = Exercise::find($id);
        $exercise->delete();
        return "Success";
    }
}
