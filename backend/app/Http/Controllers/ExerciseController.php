<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Medical_record;
use App\Family_medical_record;
use App\User_plan;
use App\measurement_record;
use App\Aerobic_test;
use App\Power_test;
use App\Routine;
use App\Exercise_Routine;
use App\Exercise;
use DB;


use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
	public function load_video(Request $request)
	{
		$ejercicio = Exercise::find($request->ejercicio_id);

		if ($request->hasFile('video')) {
			$file = $request->file('video');
			$name = $request->ejercicio_id . ".mp4";
			$file->move(public_path() . '/videos/' . $ejercicio->cod, $name);

			DB::table('exercise')
				->where('id', '=', $request->ejercicio_id)
				->update(['url' => $name]);
		}

		return redirect("/");
	}

	public function calculate_routine($id)
	{
		//$user = User::where('id', '=', $request->user()->id)->first();
		$usuario = User::find($id);
		$power_test = Power_test::where('user_id', '=', $id)
			->orderBy('id', 'DESC')
			->first();
		//return response()->json($test, 200);
		$resultado = $this->cal_peso_rep($usuario->objective, $power_test->level, $power_test->result_75);
		$power_test->weight = $resultado[0];
		$power_test->repetitions = $resultado[1];
		$resultado2 = $this->cal_peso_rep($usuario->objective, $power_test->level_2, $power_test->result_75_2);
		$power_test->weight_2 = $resultado2[0];
		$power_test->repetitions_2 = $resultado2[1];
		$resultado3 = $this->cal_peso_rep($usuario->objective, $power_test->level_3, $power_test->result_75_3);
		$power_test->weight_3 = $resultado3[0];
		$power_test->repetitions_3 = $resultado3[1];
		$power_test->save();
	}


	public function cal_peso_rep($tipo, $nivel, $resultado)
	{
		$peso = null;
		$repeticiones = null;

		switch ($tipo) {

			case (0):
				switch ($nivel) {

					case (0):
						$peso = ($resultado * 55) / 75;
						$repeticiones = 10;
						break;

					case (1):
						$peso = ($resultado * 61) / 75;
						$repeticiones = 10;
						break;

					case (2):
						$peso = ($resultado * 66) / 75;
						$repeticiones = 10;
						break;

					case (3):
						$peso = ($resultado * 70) / 75;
						$repeticiones = 10;
						break;

					case (4):
						$peso = ($resultado * 72) / 75;
						$repeticiones = 12;
						break;
				}
				break;

			case (1):
				switch ($nivel) {

					case (0):
						$peso = ($resultado * 67) / 75;
						$repeticiones = 10;
						break;

					case (1):
						$peso = ($resultado * 70) / 75;
						$repeticiones = 10;
						break;

					case (2):
						$peso = ($resultado * 75) / 75;
						$repeticiones = 8;
						break;

					case (3):
						$peso = ($resultado * 75) / 75;
						$repeticiones = 8;
						break;

					case (4):
						$peso = ($resultado * 75) / 75;
						$repeticiones = 6;
						break;
				}
				break;

			case (2):
				switch ($nivel) {

					case (0):
						$peso = ($resultado * 50) / 75;
						$repeticiones = 10;
						break;

					case (1):
						$peso = ($resultado * 61) / 75;
						$repeticiones = 10;
						break;

					case (2):
						$peso = ($resultado * 66) / 75;
						$repeticiones = 15;
						break;

					case (3):
						$peso = ($resultado * 71) / 75;
						$repeticiones = 18;
						break;

					case (4):
						$peso = ($resultado * 71) / 75;
						$repeticiones = 20;
						break;
				}
				break;
		}

		$result = [];
		$result[0] = $peso;
		$result[1] = $repeticiones;
		return $result;
	}

	public function routine(Request $request)
	{
		//$rutina= new Routine();
		$usuario = User::find($request->user()->id);

		$test = DB::table('power_test')
			->select('id', 'level', 'level_2', 'level_3')
			->where('user_id', '=', $usuario->id)
			->orderBy('id', 'DESC')
			->first();

		if (is_null($test)) {
			return response()->json(['Usuario No ha realizado el test'], 200);
		}

		$dias = Routine::where('user_id', '=', $request->user()->id)
			->where('power_test_id', '=', $test->id)
			->orderBy('id', 'DESC')
			->first();


		if (!is_null($dias)) {
			$dia = $dias->day;

			if ($dias->ready == 0) {
				$rutina2 = $this->daily_routine($usuario->id, 0);
				return response()->json(['routine' => $dias->id, 'day' => $dias->day, 'exercises' => $rutina2], 200);
			}
		} else {
			$dia = 0;
		}


		if ($dia >= 30) {
			return response()->json(['Ya completo su rutina, debe realizar el nuevo test'], 200);
		}

		$ej = [];
		$rutina = new Routine();
		$rutina->user_id = $usuario->id;
		$rutina->power_test_id = $test->id;
		$rutina->day = $dia + 1;
		$rutina->save();

		//return response()->json($test);

		switch ($usuario->objective) {
			case (0):
				if ($dia == 0 or $dia == 10 or $dia == 20) {


					$ej[1] = $this->exercise("I", $usuario->id, $tes->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("I", $usuario->id, $tes->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("E", $usuario->id, $tes->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[9] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
						} elseif ($i > 8 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 11 or $dia == 21) {


					$ej[1] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[9] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
						} elseif ($i > 8 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 12 or $dia == 22) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[9] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
						} elseif ($i > 8 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 13 or $dia == 23) {


					$ej[1] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[9] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
						} elseif ($i > 8 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 14 or $dia == 24) {


					$ej[1] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[9] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
						} elseif ($i > 8 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 15 or $dia == 25) {


					$ej[1] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[11] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 16 or $dia == 26) {


					$ej[1] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[11] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 17 or $dia == 27) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[11] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 18 or $dia == 28) {


					$ej[1] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[11] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 9 or $dia == 19 or $dia == 29) {


					$ej[1] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[11] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				}
				break;

			case (1):
				if ($dia == 0 or $dia == 10 or $dia == 20) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 11 or $dia == 21) {


					$ej[1] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 12 or $dia == 22) {


					$ej[1] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 13 or $dia == 23) {


					$ej[1] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 14 or $dia == 24) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 15 or $dia == 25) {


					$ej[1] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[3] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[6] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[8] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[12] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[13] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 14; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 2) {
							$ejercicio1->stage = 1;
						} elseif ($i > 2 and $i <= 5) {
							$ejercicio1->stage = 2;
						} elseif ($i > 5 and $i <= 7) {
							$ejercicio1->stage = 3;
						} elseif ($i > 7 and $i <= 11) {
							$ejercicio1->stage = 4;
						} elseif ($i > 11 and $i <= 13) {
							$ejercicio1->stage = 5;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 16 or $dia == 26) {


					$ej[1] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[3] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[6] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[8] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[12] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[13] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 14; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 2) {
							$ejercicio1->stage = 1;
						} elseif ($i > 2 and $i <= 5) {
							$ejercicio1->stage = 2;
						} elseif ($i > 5 and $i <= 7) {
							$ejercicio1->stage = 3;
						} elseif ($i > 7 and $i <= 11) {
							$ejercicio1->stage = 4;
						} elseif ($i > 11 and $i <= 13) {
							$ejercicio1->stage = 5;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 17 or $dia == 27) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[3] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[6] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[8] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[12] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[13] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 14; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 2) {
							$ejercicio1->stage = 1;
						} elseif ($i > 2 and $i <= 5) {
							$ejercicio1->stage = 2;
						} elseif ($i > 5 and $i <= 7) {
							$ejercicio1->stage = 3;
						} elseif ($i > 7 and $i <= 11) {
							$ejercicio1->stage = 4;
						} elseif ($i > 11 and $i <= 13) {
							$ejercicio1->stage = 5;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 18 or $dia == 28) {


					$ej[1] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[3] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[6] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[8] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[12] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[13] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 14; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 2) {
							$ejercicio1->stage = 1;
						} elseif ($i > 2 and $i <= 5) {
							$ejercicio1->stage = 2;
						} elseif ($i > 5 and $i <= 7) {
							$ejercicio1->stage = 3;
						} elseif ($i > 7 and $i <= 11) {
							$ejercicio1->stage = 4;
						} elseif ($i > 11 and $i <= 13) {
							$ejercicio1->stage = 5;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 9 or $dia == 19 or $dia == 29) {


					$ej[1] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[3] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[6] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[8] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//
					$ej[12] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[13] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 14; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 2) {
							$ejercicio1->stage = 1;
						} elseif ($i > 2 and $i <= 5) {
							$ejercicio1->stage = 2;
						} elseif ($i > 5 and $i <= 7) {
							$ejercicio1->stage = 3;
						} elseif ($i > 7 and $i <= 11) {
							$ejercicio1->stage = 4;
						} elseif ($i > 11 and $i <= 13) {
							$ejercicio1->stage = 5;
						}
						$ejercicio1->save();
					}
				}
				break;

			case (2):
				if ($dia == 0 or $dia == 10 or $dia == 20) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 11 or $dia == 21) {


					$ej[1] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 12 or $dia == 22) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 13 or $dia == 23) {


					$ej[1] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 14 or $dia == 24) {


					$ej[1] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[4] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[5] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[7] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[8] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[10] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[11] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
						} elseif ($i > 9 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 15 or $dia == 25) {


					$ej[1] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[11] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 16 or $dia == 26) {


					$ej[1] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("P", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[11] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 17 or $dia == 27) {


					$ej[1] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("B", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[11] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("T", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 18 or $dia == 28) {


					$ej[1] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("E", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FE", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("I", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[11] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("Pt", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 9 or $dia == 19 or $dia == 29) {


					$ej[1] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[2] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[3] = $this->exercise("FP", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[4] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[5] = $this->exercise("C", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[6] = $this->exercise("G", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[7] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					//

					$ej[8] = $this->exercise("FT", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[9] = $this->exercise("FL", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[10] = $this->exercise("D", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					//
					$ej[11] = $this->exercise("ZM", $usuario->id, $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);
					$ej[12] = $this->exercise("T", $usuario->id,  $test->level, $test->level_2, $test->level_3, 1, $dia + 1, $usuario->objective);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_id = $rutina->id;
						$resp = explode("-", $ej[$i]);
						$ejercicio1->exercise_id = $resp[0];
						$ejercicio1->cadence_id = $resp[1];

						if ($i <= 4) {
							$ejercicio1->stage = 1;
						} elseif ($i > 4 and $i <= 7) {
							$ejercicio1->stage = 2;
						} elseif ($i > 7 and $i <= 10) {
							$ejercicio1->stage = 3;
						} elseif ($i > 10 and $i <= 12) {
							$ejercicio1->stage = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia > 30) {
					return response()->json(['message' => 'Usuario ya completo su rutina'], 200);
				}
				break;
		}

		//return response()->json(['message' => $ej], 200);
		$rutina2 = $this->daily_routine($usuario->id, 0);
		return response()->json(['routine' => $rutina->id, 'day' => $rutina->day, 'exercises' => $rutina2], 200);
	}

	public function daily_routine($usuario_id, $opc)
	{
		$t = DB::table('routine')
			->select('id')
			->where('user_id', '=', $usuario_id)
			->where('ready', '=', 0)
			->orderBy('id', 'DESC')
			->first();

		$rutina = Exercise_Routine::where('routine_id', '=', $t->id)
			->get();

		$ejercicios = collect();
		foreach ($rutina as $rut) {
			$ejercicio = DB::table('exercise')
				->where('id', '=', $rut->exercise_id)
				->first();
			$cadencia = DB::table('cadence')
				->where('id', '=', $rut->cadence_id)
				->first();

			$ejercicio->stage = $rut->stage;

			$ejercicio->cadence_up = $cadencia->up;
			$ejercicio->cadence_down = $cadencia->down;

			$tipo = $ejercicio->cod;
			if (($tipo == 'D') or ($tipo == 'P') or ($tipo == 'T') or ($tipo == 'FE')) {
				$power_t = DB::table('power_test')
					->select('weight', 'repetitions')
					->where('user_id', '=', $usuario_id)
					->first();
				$ejercicio->weight = $power_t->weight;
				$ejercicio->repetitions = $power_t->repetitions;
			} else if (($tipo == 'E') or ($tipo == 'B') or ($tipo == 'FP')) {
				$power_t = DB::table('power_test')
					->select('weight_2', 'repetitions_2')
					->where('user_id', '=', $usuario_id)
					->first();
				$ejercicio->weight = $power_t->weight_2;
				$ejercicio->repetitions = $power_t->repetitions_2;
			} else if (($tipo == 'I') or ($tipo == 'G') or ($tipo == 'C') or ($tipo == 'Pt') or ($tipo == 'FT') or ($tipo == 'ZM')) {
				$power_t = DB::table('power_test')
					->select('weight_3', 'repetitions_3')
					->where('user_id', '=', $usuario_id)
					->first();
				$ejercicio->weight = $power_t->weight_3;
				$ejercicio->repetitions = $power_t->repetitions_3;
			}

			$ejercicios->push($ejercicio);
		}

		return $ejercicios;
	}


	public function exercise($tipo, $usuario, $nivel0, $nivel1, $nivel2, $n, $dia, $obj)
	{
		$ejerciciosRealizados = DB::table('exercise_routine')
			->select('exercise_routine.*', DB::raw('COUNT(*) AS RecuentoFilas'))
			->where('user_id', '=', $usuario)
			->groupBy('exercise_id')
			->havingRaw('COUNT(*) = ?', [$n])
			->get();
		$ejerciciosArray = array();
		foreach ($ejerciciosRealizados as $ejerc) {
			array_push($ejerciciosArray, $ejerc->exercise_id);
		}

		$nivel = 0;

		if (($tipo == 'D') or ($tipo == 'P') or ($tipo == 'T') or ($tipo == 'FE')) {
			$nivel = $nivel0;
		} else if (($tipo == 'E') or ($tipo == 'B') or ($tipo == 'FP')) {
			$nivel = $nivel1;
		} else if (($tipo == 'I') or ($tipo == 'G') or ($tipo == 'C') or ($tipo == 'Pt') or ($tipo == 'FT') or ($tipo == 'ZM')) {
			$nivel = $nivel2;
		}

		if ($nivel > 1) {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->whereNotIn('id', $ejerciciosArray)
				->get();
		} else {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->where('pro', '=', 0)
				->whereNotIn('id', $ejerciciosArray)
				->get();
		}

		$cant = $ejerciciosDisponibles->count();


		if ($cant == 0) {
			$this->exercise($tipo, $usuario, $nivel0, $nivel1, $nivel2, $n + 1, $dia, $obj);
		} else {
			$ejer = $ejerciciosDisponibles->random();
		}

		$dia_3 = $dia;
		while ($dia_3 > 3) {
			$dia_3 = $dia_3 - 3;
		}

		$cadencia = DB::table('cadence')
			->select('id')
			->where('objective', '=', $obj)
			->where('level', '=', $nivel)
			->where('day', '=', $dia_3)
			->first();

		return $ejer->id . "-" . $cadencia->id;
	}

	public function exerciseRandom($usuario, $Rutine_exercise_id, $id_exercise, $n)
	{

		$ejerciciosRealizados = DB::table('exercise_routine')
			->select('exercise_routine.*', DB::raw('COUNT(*) AS RecuentoFilas'))
			->where('user_id', '=', $usuario)
			->groupBy('exercise_id')
			->havingRaw('COUNT(*) = ?', [$n])
			->get();
		$ejerciciosArray = array();
		foreach ($ejerciciosRealizados as $ejerc) {
			array_push($ejerciciosArray, $ejerc->exercise_id);
		}

		$nivel = 0;
		$exercise = DB::table('exercise')
			->select('cod')
			->where('id', '=', $id_exercise)
			->first();
		$tipo = $exercise->cod;

		if (($tipo == 'D') or ($tipo == 'P') or ($tipo == 'T') or ($tipo == 'FE')) {
			$power_t = DB::table('power_test')
				->select('level')
				->where('user_id', '=', $usuario)
				->first();

			$nivel = $power_t->level;
		} else if (($tipo == 'E') or ($tipo == 'B') or ($tipo == 'FP')) {
			$power_t = DB::table('power_test')
				->select('level_2')
				->where('user_id', '=', $usuario)
				->first();

			$nivel = $power_t->level_2;
		} else if (($tipo == 'I') or ($tipo == 'G') or ($tipo == 'C') or ($tipo == 'Pt') or ($tipo == 'FT') or ($tipo == 'ZM')) {
			$power_t = DB::table('power_test')
				->select('level_3')
				->where('user_id', '=', $usuario)
				->first();

			$nivel = $power_t->level_3;
		}

		if ($nivel > 1) {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->whereNotIn('id', $ejerciciosArray)
				->get();
		} else {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->where('pro', '=', 0)
				->whereNotIn('id', $ejerciciosArray)
				->get();
		}

		$cant = $ejerciciosDisponibles->count();


		if ($cant == 0) {
			$this->exerciseRandom($usuario, $Rutine_exercise_id, $id_exercise, $n + 1);
		} else {
			$ejer = $ejerciciosDisponibles->random();
		}

		$exercise_routine = Exercise_Routine::where("routine_id", "=", $Rutine_exercise_id)->where("exercise_id", "=", $id_exercise)->first();
		if (!is_null($exercise_routine)) {
			$exercise_routine->exercise_id = $ejer->id;
			$exercise_routine->save();

			return response()->json($ejer);
		} else {
			return response()->json("ejercicio no encontrada");
		}
	}

	public function exerciseAvailable($usuario, $id_exercise, $n)
	{

		$ejerciciosRealizados = DB::table('exercise_routine')
			->select('exercise_routine.*', DB::raw('COUNT(*) AS RecuentoFilas'))
			->where('user_id', '=', $usuario)
			->groupBy('exercise_id')
			->havingRaw('COUNT(*) = ?', [$n])
			->get();
		$ejerciciosArray = array();
		foreach ($ejerciciosRealizados as $ejerc) {
			array_push($ejerciciosArray, $ejerc->exercise_id);
		}

		$nivel = 0;
		$exercise = DB::table('exercise')
			->select('cod')
			->where('id', '=', $id_exercise)
			->first();
		$tipo = $exercise->cod;

		if (($tipo == 'D') or ($tipo == 'P') or ($tipo == 'T') or ($tipo == 'FE')) {
			$power_t = DB::table('power_test')
				->select('level')
				->where('user_id', '=', $usuario)
				->first();

			$nivel = $power_t->level;
		} else if (($tipo == 'E') or ($tipo == 'B') or ($tipo == 'FP')) {
			$power_t = DB::table('power_test')
				->select('level_2')
				->where('user_id', '=', $usuario)
				->first();

			$nivel = $power_t->level_2;
		} else if (($tipo == 'I') or ($tipo == 'G') or ($tipo == 'C') or ($tipo == 'Pt') or ($tipo == 'FT') or ($tipo == 'ZM')) {
			$power_t = DB::table('power_test')
				->select('level_3')
				->where('user_id', '=', $usuario)
				->first();

			$nivel = $power_t->level_3;
		}

		if ($nivel > 1) {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->whereNotIn('id', $ejerciciosArray)
				->get();
		} else {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->where('pro', '=', 0)
				->whereNotIn('id', $ejerciciosArray)
				->get();
		}

		$cant = $ejerciciosDisponibles->count();


		if ($cant == 0) {
			$this->exerciseRandom($Rutine_exercise_id, $id_exercise, $n + 1);
		} else {

			return response()->json($ejerciciosDisponibles);
		}
	}

	public function updateExercise($Rutine_exercise_id, $id_exercise_v, $id_exercise_n)
	{

		$exercise_routine = Exercise_Routine::where("routine_id", "=", $Rutine_exercise_id)
			->where("exercise_id", "=", $id_exercise_v)
			->orderBy("id", 'DESC')
			->first();

		if (!is_null($exercise_routine)) {
			$exercise_routine->exercise_id = $id_exercise_n;
			$exercise_routine->save();
			return response()->json(['message' => 'Success'], 200);
		} else {
			return response()->json("ejercicio no encontrada");
		}
	}

	public function updateRoutine(Request $request)
	{
		$routine = Routine::where('user_id', "=", $request->user()->id)->orderBy('id', 'DESC')->first();
		$routine->ready = 1;
		$routine->calf = $request->calf;
		$routine->save();
		return response()->json(['message' => 'Success'], 200);
	}
}
