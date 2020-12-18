<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Medical_record;
use App\Family_medical_record;
use App\User_plan;
use App\Stretching;
use App\measurement_record;
use App\Aerobic_test;
use App\Home_test;
use App\Routine_home;
use App\Exercise_Home_Routine;
use DB;

use Illuminate\Support\Facades\Auth;

class ExerciseHomeController extends Controller
{

	public function home_test(Request $request)
	{

		try {
			$request->validate(['result' => 'required',]);
			$home_test = new Home_test($request->all());
			$home_test->user_id = $request->user()->id;
			$nivel = 0;
			if ($request->result >= 5 && $request->result < 9) {
				$nivel = 1;
			} else if ($request->result >= 9 && $request->result < 13) {
				$nivel = 2;
			} else if ($request->result >= 13) {
				$nivel = 3;
			}
			$home_test->level = $nivel;
			$home_test->save();
			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}


	public function routine(Request $request)
	{
		//$rutina= new Routine_home();
		$usuario = User::find($request->user()->id);

		$test = DB::table('home_test')
			->select('id', 'level')
			->where('user_id', '=', $usuario->id)
			->orderBy('id', 'DESC')
			->first();

		if (is_null($test)) {
			return response()->json(['Usuario No ha realizado el test'], 200);
		}

		$dias = Routine_home::where('user_id', '=', $request->user()->id)
			->where('test_home_id', '=', $test->id)
			->orderBy('id', 'DESC')
			->first();

		if (!is_null($dias)) {
			$dia = $dias->day;

			if ($dias->ready == 0) {
				$rutina2 = $this->routine_exercise2($usuario->id, 0);
				$stage = $this->routine_state($usuario->id, 0);
				return response()->json(['routine' => $dias->id, 'day' => $dias->day, 'ratio_w' => $dias->ratio_w, 'ratio_r' => $dias->ratio_r, 'stages' => $stage, 'exercises' => $rutina2], 200);
			}
		} else {
			$dia = 0;
		}

		if ($dia >= 30) {
			return response()->json(['Ya completo su rutina, debe realizar el nuevo test'], 200);
		}



		$ej = [];
		$rutina = new Routine_home();
		$rutina->user_id = $usuario->id;
		$rutina->test_home_id = $test->id;
		$rutina->day = $dia + 1;
		$ratio = $this->ratio($test->level, $dia + 1);
		$rutina->ratio_w = $ratio[0];
		$rutina->ratio_r = $ratio[1];
		$rutina->save();

		switch ($test->level) {
			case (0):
				if ($dia == 0 or $dia == 15) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 16) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 17) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 18) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 19) {


					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 20) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 4 and $i <= 8) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 21) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 22) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 4 and $i <= 8) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 23) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 9 or $dia == 24) {


					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 4) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 4 and $i <= 8) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 10 or $dia == 25) {


					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);

					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 4;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 11 or $dia == 26) {


					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);

					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 4;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 12 or $dia == 27) {


					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 4;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 13 or $dia == 28) {


					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);

					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 4;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 14 or $dia == 29) {


					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);

					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 4;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				}
				break;

			case (1):
				if ($dia == 0 or $dia == 15) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 16) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 17) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 18) {


					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 19) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 20) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TS", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 5) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 5 and $i <= 10) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 21) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 5) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 5 and $i <= 10) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 22) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 2;
						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 23) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TS", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 5) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 5 and $i <= 10) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 9 or $dia == 24) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);

					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 5) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 5 and $i <= 10) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 10 or $dia == 25) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 11 or $dia == 26) {


					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 12 or $dia == 27) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 13 or $dia == 28) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 14 or $dia == 29) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);

					for ($i = 1; $i < 8; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				}
				break;

			case (2):
				if ($dia == 0 or $dia == 15) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 16) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 17) {

					$ej[1] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 18) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 19) {


					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 10; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 9) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 3;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 20) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 21) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 22) {


					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 23) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} else	if ($dia == 9 or $dia == 24) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 10 or $dia == 25) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} elseif ($dia == 11 or $dia == 26) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 4;
						$ejercicio1->save();
					}
				} elseif ($dia == 12 or $dia == 27) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} elseif ($dia == 13 or $dia == 28) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} else	if ($dia == 14 or $dia == 29) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				}
				break;

			case (3):
				if ($dia == 0 or $dia == 15) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 1 or $dia == 16) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					//
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 2 or $dia == 17) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CO", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 3 or $dia == 18) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 4 or $dia == 19) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);

					//
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					//
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 9; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 3) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 3 and $i <= 6) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 3;
						} elseif ($i > 6 and $i <= 8) {
							$ejercicio1->stage = 3;
							$ejercicio1->repetitions = 4;
						}
						$ejercicio1->save();
					}
				} elseif ($dia == 5 or $dia == 20) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} elseif ($dia == 6 or $dia == 21) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 7; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 4;
						$ejercicio1->save();
					}
				} elseif ($dia == 7 or $dia == 22) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} elseif ($dia == 8 or $dia == 23) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TI", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} else	if ($dia == 9 or $dia == 24) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);

					$ej[7] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[11] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[12] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 13; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						if ($i <= 6) {
							$ejercicio1->stage = 1;
							$ejercicio1->repetitions = 2;
						} elseif ($i > 6 and $i <= 12) {
							$ejercicio1->stage = 2;
							$ejercicio1->repetitions = 2;
						}

						$ejercicio1->save();
					}
				} elseif ($dia == 10 or $dia == 25) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 11 or $dia == 26) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 12 or $dia == 27) {

					$ej[1] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CR", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 13 or $dia == 28) {

					$ej[1] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("CO", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TS", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("CO", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				} elseif ($dia == 14 or $dia == 29) {

					$ej[1] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[2] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[3] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[4] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[5] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[6] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[7] = $this->exercise("CR", $usuario->id, 1, $ej);
					$ej[8] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[9] = $this->exercise("TI", $usuario->id, 1, $ej);
					$ej[10] = $this->exercise("TI", $usuario->id, 1, $ej);

					for ($i = 1; $i < 11; $i++) {
						$ejercicio1 = new Exercise_Home_Routine();
						$ejercicio1->user_id = $usuario->id;
						$ejercicio1->routine_home_id = $rutina->id;
						$ejercicio1->exercise_id = $ej[$i];
						$ejercicio1->stage = 1;
						$ejercicio1->repetitions = 3;
						$ejercicio1->save();
					}
				}
				break;
		}


		$rutina2 = $this->routine_exercise2($usuario->id, 0);
		$stage = $this->routine_state($usuario->id, 0);
		return response()->json(['routine' => $rutina->id, 'day' => $rutina->day, 'ratio_w' => $rutina->ratio_w, 'ratio_r' => $rutina->ratio_r, 'stages' => $stage, 'exercises' => $rutina2], 200);
	}


	public function daily_routine($usuario_id, $opc)
	{


		$t = DB::table('routine_home')
			->select('id', 'day')
			->where('user_id', '=', $usuario_id)
			->where('ready', '=', 0)
			->orderBy('id', 'DESC')
			->first();

		$rutina = Exercise_Home_Routine::where('routine_home_id', '=', $t->id)
			->get();




		$ejercicios = collect();
		foreach ($rutina as $rut) {
			$ejercicio = DB::table('exercise')
				->where('id', '=', $rut->exercise_id)
				->first();
			$ejercicio->stage = $rut->stage;
			$ejercicio->repetitions = $rut->repetitions;
			$ejercicios->push($ejercicio);
		}


		return $ejercicios;
	}



	public function routine_exercise2($usuario_id, $opc)
	{
		$t = DB::table('routine_home')
			->select('id', 'day')
			->where('user_id', '=', $usuario_id)
			->where('ready', '=', 0)
			->orderBy('id', 'DESC')
			->first();
		$ejercicios = collect();
		$stages = Exercise_Home_Routine::select('stage')
			->where('routine_home_id', '=', $t->id)
			->groupBy('stage')
			->get();
		foreach ($stages as $estado) {

			$rutina = Exercise_Home_Routine::where('routine_home_id', '=', $t->id)
				->where('stage', '=', $estado->stage)
				->get();


			for ($i = 1; $i <= $rutina[0]->repetitions; $i++) {
				foreach ($rutina as $rut) {
					$ejercicio = DB::table('exercise')
						->where('id', '=', $rut->exercise_id)
						->first();
					$ejercicio->stage = $rut->stage;
					//$ejercicio->repetitions= $rut->repetitions;
					$ejercicios->push($ejercicio);
				}
			}
		}


		return $ejercicios;
	}


	public function exercise($tipo, $usuario, $n, $ejercicios_por_cargar)
	{

		$nivel_test = DB::table('home_test')
			->select('level')
			->where('user_id', '=', $usuario)
			->orderBy('id', 'DESC')->first();
		$nivel = $nivel_test->level;

		$ejerciciosRealizados = DB::table('exercise_home_routine')
			->select('exercise_home_routine.*', DB::raw('COUNT(*) AS RecuentoFilas'))
			->where('user_id', '=', $usuario)
			->groupBy('exercise_id')
			->havingRaw('COUNT(*) >= ?', [$n])
			->get();
		$ejerciciosArray = array();
		foreach ($ejerciciosRealizados as $ejerc) {
			array_push($ejerciciosArray, $ejerc->exercise_id);
		}
		$ejercicio_no_disponibles = array_merge($ejerciciosArray, $ejercicios_por_cargar);

		if ($nivel >= 1) {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->whereNotIn('id', $ejercicio_no_disponibles)
				->get();
		} else {
			$ejerciciosDisponibles = DB::table('exercise')
				->where('cod', '=', $tipo)
				->where('pro', '=', 0)
				->whereNotIn('id', $ejercicio_no_disponibles)
				->get();
		}

		$cant = $ejerciciosDisponibles->count();
		if ($cant == 0) {
			return $this->exercise($tipo, $usuario, $n + 1, $ejercicios_por_cargar);
		} else {
			$ejer = $ejerciciosDisponibles->random();
		}
		//return response()->json(['message' => $ejercicio_no_disponibles], 200);
		return $ejer->id;
	}

	public function updateRoutine(Request $request)
	{
		$routine = Routine_Home::where('user_id', "=", $request->user()->id)->orderBy('id', 'DESC')->first();
		$routine->ready = 1;
		$routine->calf = $request->calf;
		$routine->save();
		return response()->json(['message' => 'Success'], 200);
	}

	public function ratio($nivel, $dia)
	{
		//***********************************CALCULO RATIO*********************************************	
		$ratio = array();
		if ($nivel == 0) {
			if (($dia >= 1 && $dia <= 5) or ($dia >= 16 && $dia <= 20)) {
				$ratio[0] = 30 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 30;
			}
			if (($dia >= 6 && $dia <= 10) or ($dia >= 21 && $dia <= 25)) {
				$ratio[0] = 40 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 40;
			}
			if (($dia >= 11 && $dia <= 15) or ($dia >= 26 && $dia <= 30)) {
				$ratio[0] = 35 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 35;
			}
		} elseif ($nivel == 1) {
			if (($dia >= 1 && $dia <= 5) or ($dia >= 16 && $dia <= 20)) {
				$ratio[0] = 35 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 25;
			}
			if (($dia >= 6 && $dia <= 10) or ($dia >= 21 && $dia <= 25)) {
				$ratio[0] = 45 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 35;
			}
			if (($dia >= 11 && $dia <= 15) or ($dia >= 26 && $dia <= 30)) {
				$ratio[0] = 40 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 30;
			}
		} elseif ($nivel == 2) {
			if (($dia >= 1 && $dia <= 5) or ($dia >= 16 && $dia <= 20)) {
				$ratio[0] = 35 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 20;
			}
			if (($dia >= 6 && $dia <= 10) or ($dia >= 21 && $dia <= 25)) {
				$ratio[0] = 45 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 30;
			}
			if (($dia >= 11 && $dia <= 15) or ($dia >= 26 && $dia <= 30)) {
				$ratio[0] = 40 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 25;
			}
		} elseif ($nivel == 3) {
			if (($dia >= 1 && $dia <= 5) or ($dia >= 16 && $dia <= 20)) {
				$ratio[0] = 40 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 20;
			}
			if (($dia >= 6 && $dia <= 10) or ($dia >= 21 && $dia <= 25)) {
				$ratio[0] = 45 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 20;
			}
			if (($dia >= 11 && $dia <= 15) or ($dia >= 26 && $dia <= 30)) {
				$ratio[0] = 35 + 20; //se pidio agregar 20 seg de trabajo a cada ejercicio
				$ratio[1] = 10;
			}
		}
		//***********************************FIN CALCULO RATIO*********************************************		
		return $ratio;
	}

	public function Exce_routine(Request $request)
	{


		$t = DB::table('routine_home')
			->select('id', 'day')
			->where('user_id', '=', $request->user()->id)
			->where('ready', '=', 0)
			->orderBy('id', 'DESC')
			->first();

		$ejercicios = collect();
		$rutina = Exercise_Home_Routine::where('routine_home_id', '=', $t->id)
			->get();

		foreach ($rutina as $rut) {
			$ejercicio = DB::table('exercise')
				->where('id', '=', $rut->exercise_id)
				->first();

			$ejercicios->push($ejercicio);
		}

		return response()->json(['Ejercicios' => $ejercicios], 200);
	}

	public function Exce_heating()
	{


		$ejercicios_cardio = DB::table('exercise')
			->where('cod', '=', 'CR')
			->where('pro', '=', '0')
			->where('id', '<>', 511)
			->where('id', '<>', 512)
			->orderBy('id', 'DESC')
			->get();
		$ejer1 = $ejercicios_cardio->random(4);

		return response()->json(['ejercicios Calentamiento' => $ejer1], 200);
	}

	public function exerciseHomeAvailable($usuario, $id_exercise, $n)
	{

		$ejerciciosRealizados = DB::table('exercise_home_routine')
			->select('exercise_home_routine.*', DB::raw('COUNT(*) AS RecuentoFilas'))
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

		$nivel_test = DB::table('home_test')
			->select('level')
			->where('user_id', '=', $usuario)
			->orderBy('id', 'DESC')->first();
		$nivel = $nivel_test->level;

		if ($nivel >= 1) {
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
			return $this->exerciseHomeAvailable($usuario, $id_exercise, $n + 1);
		} else {
			return response()->json($ejerciciosDisponibles);
		}
	}

	public function updateExerciseHome($Rutine_exercise_id, $id_exercise_v, $id_exercise_n)
	{

		$exercise_home_routine = Exercise_Home_Routine::where("routine_home_id", "=", $Rutine_exercise_id)
			->where("exercise_id", "=", $id_exercise_v)
			->orderBy("id", 'DESC')
			->first();

		if (!is_null($exercise_home_routine)) {
			$exercise_home_routine->exercise_id = $id_exercise_n;
			$exercise_home_routine->save();
			return response()->json(['message' => 'Success'], 200);
		} else {
			return response()->json("ejercicio no encontrada");
		}
	}


	//----------------obteniendo estados total de la rutina-----------------
	public function routine_state($usuario_id, $opc)
	{
		$t = DB::table('routine_home')
			->select('id', 'day')
			->where('user_id', '=', $usuario_id)
			->where('ready', '=', 0)
			->orderBy('id', 'DESC')
			->first();
		$stages = Exercise_Home_Routine::select('stage')
			->where('routine_home_id', '=', $t->id)
			->groupBy('stage')
			->orderBy('id', 'DESC')
			->first();
		return $stages->stage;
	}

	public function stretching()
	{
		$estiramiento = Stretching::get();
		return response()->json(['Estiramiento' => $estiramiento], 200);
	}
}
