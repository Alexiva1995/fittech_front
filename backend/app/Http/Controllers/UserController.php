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
use App\Food_measures;
use App\Grease_record;
use Mail;
use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

	public function verify_email(Request $request)
	{

		$user = User::where("email", "=", $request->email)->first();

		if (!is_null($user)) {
			return response()->json(['email' => 1], 200);
		} else {
			return response()->json(['email' => 0], 200);
		}
	}

	public function heart_rate(Request $request)
	{
		try {

			$request->validate([
				'heart_rate'    => 'required',
			]);


			$user = User::find($request->user()->id);
			$Medical_record = Medical_record::where('user_id', '=', $request->user()->id)->first();
			$Medical_record->heart_rate = $request->heart_rate;
			$Medical_record->save();
			$status = null;

			switch ($user->age) {

				case (0):

					if ($user->gender == 0) {

						switch ($request->heart_rate) {

							case ($request->heart_rate <= 70):
								$status = 3;
								break;

							case ($request->heart_rate >= 71 && $request->heart_rate <= 76):
								$status = 2;
								break;

							case ($request->heart_rate >= 77 && $request->heart_rate <= 94):
								$status = 1;
								break;

							case ($request->heart_rate >= 95):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					} else {

						switch ($request->heart_rate) {

							case ($request->heart_rate <= 60):
								$status = 3;
								break;

							case ($request->heart_rate >= 61 && $request->heart_rate <= 68):
								$status = 2;
								break;

							case ($request->heart_rate >= 69 && $request->heart_rate <= 84):
								$status = 1;
								break;

							case ($request->heart_rate >= 85):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					}

					break;

				case (1):

					if ($user->gender == 0) {

						switch ($request->heart_rate) {

							case ($request->heart_rate <= 70):
								$status = 3;
								break;

							case ($request->heart_rate >= 71 && $request->heart_rate <= 78):
								$status = 2;
								break;

							case ($request->heart_rate >= 79 && $request->heart_rate <= 96):
								$status = 1;
								break;

							case ($request->heart_rate >= 97):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					} else {
						switch ($request->heart_rate) {

							case ($request->heart_rate <= 62):
								$status = 3;
								break;

							case ($request->heart_rate >= 63 && $request->heart_rate <= 70):
								$status = 2;
								break;

							case ($request->heart_rate >= 71 && $request->heart_rate <= 84):
								$status = 1;
								break;

							case ($request->heart_rate >= 85):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					}

					break;

				case (2):

					if ($user->gender == 0) {
						switch ($request->heart_rate) {

							case ($request->heart_rate <= 72):
								$status = 3;
								break;

							case ($request->heart_rate >= 73 && $request->heart_rate <= 78):
								$status = 2;
								break;

							case ($request->heart_rate >= 79 && $request->heart_rate <= 98):
								$status = 1;
								break;

							case ($request->heart_rate >= 99):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					} else {

						switch ($request->heart_rate) {

							case ($request->heart_rate <= 64):
								$status = 3;
								break;

							case ($request->heart_rate >= 65 && $request->heart_rate <= 72):
								$status = 2;
								break;

							case ($request->heart_rate >= 73 && $request->heart_rate <= 88):
								$status = 1;
								break;

							case ($request->heart_rate >= 90):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					}

					break;

				case (3):
				case (4):
				case (5):
					if ($user->gender == 0) {

						switch ($request->heart_rate) {

							case ($request->heart_rate <= 74):
								$status = 3;
								break;

							case ($request->heart_rate >= 76 && $request->heart_rate <= 82):
								$status = 2;
								break;

							case ($request->heart_rate >= 83 && $request->heart_rate <= 102):
								$status = 1;
								break;

							case ($request->heart_rate >= 104):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					} else {

						switch ($request->heart_rate) {

							case ($request->heart_rate <= 66):
								$status = 3;
								break;

							case ($request->heart_rate >= 67 && $request->heart_rate <= 74):
								$status = 2;
								break;

							case ($request->heart_rate >= 75 && $request->heart_rate <= 88):
								$status = 1;
								break;

							case ($request->heart_rate >= 90):
								$status = 0;
								break;

							default:
								$status = 0;
						}
					}
					break;
			}
			$user->heart_rate = $status;
			$user->save();

			//----------Se hace una evaluacion a ver si la persona es acta para hacer ejercicios
			$Family_medical_record = Family_medical_record::where('user_id', '=', $request->user()->id)->first();

			if ($Medical_record->none == 0) {
				//si sufre de algun enfermedad es de riesgo alto
				$user->risk = 2;
				$user->save();
				Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
					$msg->to($user->email)->subject('welcome');
					$msg->attach('pdf/prueba.pdf');
				});
			} elseif ($user->heart_rate < 1 && $user->imc > 30) {
				//si su frecuencia cardiaca es mala y tiene obecidad es de riesgo alto
				//Luego lo mandaron a cambiar a que sea de riesgo moderado el 14/09/2020
				//enviar msj personalizado recomendando ir al medico
				$pdf = \App::make('dompdf.wrapper');
				$pdf->loadView('welcome2', compact('user'))->setPaper('a4', 'landscape');
				$output = $pdf->output();
				$path = "pdf/" . $user->id . ".pdf";
				file_put_contents($path, $output);
				$user->risk = 1;
				$user->save();
				Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
					$msg->to($user->email)->subject('welcome');
					$msg->attach('pdf/' . $user->id . '.pdf');
				});
			} elseif ($user->heart_rate < 1 || $user->imc > 30 && $Medical_record->none == 1) {
				//si su frecuencia cardiaca es mala O tiene obecidad es de riesgo alto y
				//no sufre de algun enfermedad
				$user->risk = 1;
				$user->save();
				Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
					$msg->to($user->email)->subject('welcome');
					$msg->attach('pdf/prueba.pdf');
				});
			} elseif ($user->heart_rate < 1) {
				//si su frecuencia cardiaca es mala 
				$user->risk = 1;
				$user->save();
			} elseif ($Family_medical_record->none == 0) {
				//si sus familiares tienen alguna enfermedad es de riesgo moderado
				$user->risk = 1;
				$user->save();
			} elseif ($Medical_record->none == 1 && $Family_medical_record->none == 1) {
				//si el no sufre de ninguna enfermedar y su familia tampoco
				$user->risk = 0;
				$user->save();
			}

			return response()->json(['User' => $user], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'Error'], 500);
		}
	}

	public function medical_record(Request $request)
	{
		try {

			$request->validate([
				'hypertension'  => 'required',
				'hypotension' => 'required',
				'lung_diseases' => 'required',
				'fading' => 'required',
				'diabetes_insulindependent' => 'required',
				'chest_pains' => 'required',
				'cardiac_pathologies' => 'required',
				'renal_insufficiency' => 'required',
				'unusual_fatigue' => 'required',
			]);

			$medical_record = new Medical_record($request->all());
			$medical_record->user_id = $request->user()->id;
			$medical_record->save();

			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}


	public function family_medical_record(Request $request)
	{
		try {

			$request->validate([
				'cardiac_arrhythmia'  => 'required',
				'heart_attack' => 'required',
				'heart_operation' => 'required',
				'congenital_heart_disease' => 'required',
				'early_death' => 'required',
				'high_blood_pressure' => 'required',
				'diabetes' => 'required',
			]);

			$Family_medical_record = new Family_medical_record($request->all());
			$Family_medical_record->user_id = $request->user()->id;
			$Family_medical_record->save();

			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}


	function update_weight(Request $request)
	{
		try {

			$user = User::find($request->user()->id);
			$user->weight = $request->weight;
			$user->imc = $request->weight / (($user->stature / 100) * ($user->stature / 100));

			if ($user->imc >= 19 && $user->imc < 25) {
				$user->indicator_imc = "normal";
			} elseif ($user->imc >= 25 && $user->imc < 30) {
				$user->indicator_imc = "sobre peso";
			} elseif ($user->imc >= 30 && $user->imc < 36) {
				$user->indicator_imc = "obecidad";
			}
			$user->save();
			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}


	public function measurement_record(Request $request)
	{
		try {

			$request->validate([
				'min_waist' => 'required',
				'max_waist' => 'required',
				'hip' => 'required',
				'neck' => 'required',
				'right_thigh' => 'required',
				'left_thigh' => 'required',
				'right_arm' => 'required',
				'left_arm' => 'required',
				'right_calf' => 'required',
				'left_calf' => 'required',
				'torax' => 'required',
				'weight' => 'required',
				'stature' => 'required',
			]);

			$measurement_record = new measurement_record($request->all());
			$measurement_record->user_id = $request->user()->id;
			$measurement_record->waist_hip = $request->min_waist / $request->hip;

			//guardando las fotos	
			$file_data = $request->input('front_photo');
			$file_name = 'front' . time() . $measurement_record->user_id . '.jpg'; //generating unique file name;
			if ($file_data != "") {
				Storage::disk('uploads')->put($file_name, base64_decode($file_data));
				$measurement_record->front_photo = $file_name;
			}
			$file_data1 = $request->input('profile_photo');
			$file_name1 = 'profile' . time() . $measurement_record->user_id . '.jpg'; //generating unique file name;
			if ($file_data1 != "") {
				Storage::disk('uploads')->put($file_name1, base64_decode($file_data1));
				$measurement_record->profile_photo = $file_name1;
			}
			$file_data2 = $request->input('back_photo');
			$file_name2 = 'back' . time() . $measurement_record->user_id . '.jpg'; //generating unique file name;
			if ($file_data2 != "") {
				Storage::disk('uploads')->put($file_name2, base64_decode($file_data2));
				$measurement_record->back_photo = $file_name2;
			}
			//**************** */
			$measurement_record->save();
			$user = User::find($request->user()->id);

			//************************Preguntamos Peso y Altura de nuevo */
			$user->weight = $request->weight;
			$user->stature = $request->stature;
			$user->imc = $user->weight / (($user->stature / 100) * ($user->stature / 100));
			if ($user->imc >= 19 && $user->imc < 25) {
				$user->indicator_imc = "normal";
			} elseif ($user->imc >= 25 && $user->imc < 30) {
				$user->indicator_imc = "sobre peso";
			} elseif ($user->imc >= 30 && $user->imc < 36) {
				$user->indicator_imc = "obecidad";
			}
			$user->save();
			//****************************************************************** */

			$status = 0;
			if ($user->gender == 0) {
				switch ($user->age) {

					case (1):
						if ($measurement_record->waist_hip < 0.71) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.71 && $measurement_record->waist_hip <= 0.77) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.78 && $measurement_record->waist_hip <= 0.82) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.83) {
							$status = 3;
						}
						break;

					case (2):
						if ($measurement_record->waist_hip < 0.72) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.72 && $measurement_record->waist_hip <= 0.78) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.79 && $measurement_record->waist_hip <= 0.84) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.85) {
							$status = 3;
						}

						break;

					case (3):
						if ($measurement_record->waist_hip < 0.73) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.73 && $measurement_record->waist_hip <= 0.79) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.80 && $measurement_record->waist_hip <= 0.87) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.88) {
							$status = 3;
						}

						break;

					case (4):
						if ($measurement_record->waist_hip < 0.74) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.74 && $measurement_record->waist_hip <= 0.81) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.82 && $measurement_record->waist_hip <= 0.88) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.89) {
							$status = 3;
						}
						break;

					case (5):
						if ($measurement_record->waist_hip < 0.76) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.76 && $measurement_record->waist_hip <= 0.83) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.84 && $measurement_record->waist_hip <= 0.90) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.91) {
							$status = 3;
						}

						break;
				}
			} elseif ($user->gender == 1) {
				switch ($user->age) {

					case (1):
						if ($measurement_record->waist_hip < 0.83) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.83 && $measurement_record->waist_hip <= 0.88) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.89 && $measurement_record->waist_hip <= 0.94) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.95) {
							$status = 3;
						}
						break;

					case (2):
						if ($measurement_record->waist_hip < 0.84) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.84 && $measurement_record->waist_hip <= 0.91) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.92 && $measurement_record->waist_hip <= 0.96) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 0.97) {
							$status = 3;
						}
						break;

					case (3):
						if ($measurement_record->waist_hip < 0.88) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.88 && $measurement_record->waist_hip <= 0.95) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.96 && $measurement_record->waist_hip <= 1.00) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 1.00) {
							$status = 3;
						}
						break;

					case (4):
						if ($measurement_record->waist_hip < 0.90) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.90 && $measurement_record->waist_hip <= 0.96) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.97 && $measurement_record->waist_hip <= 1.02) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 1.02) {
							$status = 3;
						}
						break;

					case (5):
						if ($measurement_record->waist_hip < 0.91) {
							$status = 0;
						} elseif ($measurement_record->waist_hip >= 0.91 && $measurement_record->waist_hip <= 0.98) {
							$status = 1;
						} elseif ($measurement_record->waist_hip >= 0.99 && $measurement_record->waist_hip <= 1.03) {
							$status = 2;
						} elseif ($measurement_record->waist_hip >= 1.03) {
							$status = 3;
						}
						break;
				}
			}

			$user->obesity_cc = $status;
			//calculo de ica
			$user->ica = $measurement_record->min_waist / $user->stature;
			$user->save();
			$Family_medical_record = Family_Medical_record::where('user_id', '=', $request->user()->id)->first();
			$Medical_record = Medical_record::where('user_id', '=', $request->user()->id)->first();


			if ($Medical_record->none == 0) {
				//si sufre de algun enfermedad es de riesgo alto
				$user->risk = 2;
				$user->save();
				Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
					$msg->to($user->email)->subject('welcome');
					$msg->attach('pdf/prueba.pdf');
				});
			} elseif (!is_null($user->heart_rate) && $user->heart_rate < 1 && $user->obesity_cc > 1) {
				//si su frecuencia cardiaca es mala y tiene obecidad es de riesgo alto
				//Luego lo mandaron a cambiar a que sea de riesgo moderado el 14/09/2020
				//enviar msj personalizado recomendando ir al medico
				$pdf = \App::make('dompdf.wrapper');
				$pdf->loadView('welcome2', compact('user'))->setPaper('a4', 'landscape');
				$output = $pdf->output();
				$path = "pdf/" . $user->id . ".pdf";
				file_put_contents($path, $output);
				$user->risk = 1;
				$user->save();
				Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
					$msg->to($user->email)->subject('welcome');
					$msg->attach('pdf/' . $user->id . '.pdf');
				});
			} elseif ($user->heart_rate < 1 || $user->obesity_cc > 1 && $Medical_record->none == 1) {
				//si su frecuencia cardiaca es mala O tiene obecidad  y
				//no sufre de algun enfermedad
				$user->risk = 1;
				$user->save();
				Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
					$msg->to($user->email)->subject('welcome');
					$msg->attach('pdf/prueba.pdf');
				});
			} elseif ($user->heart_rate < 1) {
				//si su frecuencia cardiaca es mala 
				$user->risk = 1;
				$user->save();
			} elseif ($Family_medical_record->none == 0) {
				//si sus familiares tienen alguna enfermedad es de riesgo moderado
				$user->risk = 1;
				$user->save();
			} elseif ($Medical_record->none == 1 && $Family_medical_record->none == 1) {
				//si el no sufre de ninguna enfermedar y su familia tampoco
				$user->risk = 0;
				$user->save();
			}

			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}

	public function aerobic_test(Request $request)
	{
		try {
			$request->validate(['distance' => 'required',]);

			$aerobic_test = new Aerobic_test($request->all());
			$aerobic_test->user_id = $request->user()->id;

			$user = User::find($request->user()->id);
			$status = 0;

			if ($user->gender == 0) {
				switch ($user->age) {

					case (0):
						if ($request->distance < 1500) {
							$status = 0;
						} elseif ($request->distance >= 1500 && $request->distance <= 1799) {
							$status = 1;
						} elseif ($request->distance >= 1800 && $request->distance <= 2199) {
							$status = 2;
						} elseif ($request->distance >= 2200 && $request->distance <= 2699) {
							$status = 3;
						} elseif ($request->distance >= 2700) {
							$status = 4;
						}
						break;


					case (1):
						if ($request->distance < 1500) {
							$status = 0;
						} elseif ($request->distance >= 1500 && $request->distance <= 1799) {
							$status = 1;
						} elseif ($request->distance >= 1800 && $request->distance <= 2199) {
							$status = 2;
						} elseif ($request->distance >= 2200 && $request->distance <= 2699) {
							$status = 3;
						} elseif ($request->distance >= 2700) {
							$status = 4;
						}
						break;

					case (2):
						if ($request->distance < 1400) {
							$status = 0;
						} elseif ($request->distance >= 1400 && $request->distance <= 1699) {
							$status = 1;
						} elseif ($request->distance >= 1700 && $request->distance <= 1999) {
							$status = 2;
						} elseif ($request->distance >= 2000 && $request->distance <= 2500) {
							$status = 3;
						} elseif ($request->distance > 2500) {
							$status = 4;
						}
						break;

					case (3):
						if ($request->distance < 1200) {
							$status = 0;
						} elseif ($request->distance >= 1200 && $request->distance <= 1499) {
							$status = 1;
						} elseif ($request->distance >= 1500 && $request->distance <= 1899) {
							$status = 2;
						} elseif ($request->distance >= 1900 && $request->distance <= 2300) {
							$status = 3;
						} elseif ($request->distance > 2300) {
							$status = 4;
						}
						break;

					case (4):
						if ($request->distance < 1100) {
							$status = 0;
						} elseif ($request->distance >= 1100 && $request->distance <= 1399) {
							$status = 1;
						} elseif ($request->distance >= 1400 && $request->distance <= 1699) {
							$status = 2;
						} elseif ($request->distance >= 1700 && $request->distance <= 2200) {
							$status = 3;
						} elseif ($request->distance > 2200) {
							$status = 4;
						}
						break;
					case (5):
						if ($request->distance < 1100) {
							$status = 0;
						} elseif ($request->distance >= 1100 && $request->distance <= 1399) {
							$status = 1;
						} elseif ($request->distance >= 1400 && $request->distance <= 1699) {
							$status = 2;
						} elseif ($request->distance >= 1700 && $request->distance <= 2200) {
							$status = 3;
						} elseif ($request->distance > 2200) {
							$status = 4;
						}
						break;
				}
			} elseif ($user->gender == 1) {


				switch ($user->age) {

					case (0):
						if ($request->distance < 1600) {
							$status = 0;
						} elseif ($request->distance >= 1600 && $request->distance <= 2199) {
							$status = 1;
						} elseif ($request->distance >= 2200 && $request->distance <= 2399) {
							$status = 2;
						} elseif ($request->distance >= 2400 && $request->distance <= 2800) {
							$status = 3;
						} elseif ($request->distance > 2800) {
							$status = 4;
						}
						break;


					case (1):
						if ($request->distance < 1600) {
							$status = 0;
						} elseif ($request->distance >= 1600 && $request->distance <= 2199) {
							$status = 1;
						} elseif ($request->distance >= 2200 && $request->distance <= 2399) {
							$status = 2;
						} elseif ($request->distance >= 2400 && $request->distance <= 2800) {
							$status = 3;
						} elseif ($request->distance > 2800) {
							$status = 4;
						}
						break;

					case (2):
						if ($request->distance < 1500) {
							$status = 0;
						} elseif ($request->distance >= 1500 && $request->distance <= 1899) {
							$status = 1;
						} elseif ($request->distance >= 1900 && $request->distance <= 2299) {
							$status = 2;
						} elseif ($request->distance >= 2300 && $request->distance <= 2700) {
							$status = 3;
						} elseif ($request->distance > 2700) {
							$status = 4;
						}
						break;

					case (3):
						if ($request->distance < 1400) {
							$status = 0;
						} elseif ($request->distance >= 1400 && $request->distance <= 1699) {
							$status = 1;
						} elseif ($request->distance >= 1700 && $request->distance <= 2099) {
							$status = 2;
						} elseif ($request->distance >= 2100 && $request->distance <= 2500) {
							$status = 3;
						} elseif ($request->distance > 2500) {
							$status = 4;
						}
						break;

					case (4):
						if ($request->distance < 1300) {
							$status = 0;
						} elseif ($request->distance >= 1300 && $request->distance <= 1599) {
							$status = 1;
						} elseif ($request->distance >= 1600 && $request->distance <= 1999) {
							$status = 2;
						} elseif ($request->distance >= 2000 && $request->distance <= 2400) {
							$status = 3;
						} elseif ($request->distance > 2400) {
							$status = 4;
						}
						break;
					case (5):
						if ($request->distance < 1300) {
							$status = 0;
						} elseif ($request->distance >= 1300 && $request->distance <= 1599) {
							$status = 1;
						} elseif ($request->distance >= 1600 && $request->distance <= 1999) {
							$status = 2;
						} elseif ($request->distance >= 2000 && $request->distance <= 2400) {
							$status = 3;
						} elseif ($request->distance > 2400) {
							$status = 4;
						}
						break;
				}
			}
			$aerobic_test->result = $status;
			$aerobic_test->save();
			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}


	public function power_test(Request $request)
	{
		try {
			$request->validate(['result_75' => 'required', 'exercise' => 'required',]);

			$power_test = new Power_test($request->all());
			$power_test->user_id = $request->user()->id;

			$user = User::find($request->user()->id);
			$power_test->level = $this->cal($user->gender, $request->exercise, $request->result_75);
			$power_test->level_2 = $this->cal($user->gender, $request->exercise_2, $request->result_75_2);
			$power_test->level_3 = $this->cal($user->gender, $request->exercise_3, $request->result_75_3);
			$power_test->save();

			$controller = new ExerciseController();
			$controller->calculate_routine($user->id);

			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}

	public function cal($sexo, $exercise, $result)
	{
		$status = null;
		if ($sexo == 0) {

			switch ($exercise) {

				case (1):
					if ($result < 20) {
						$status = 0;
					} elseif ($result >= 20 && $result < 30) {
						$status = 2;
					} elseif ($result >= 30 && $result < 45) {
						$status = 3;
					} elseif ($result >= 45) {
						$status = 4;
					}
					break;


				case (2):
					if ($result < 20) {
						$status = 0;
					} elseif ($result >= 20 && $result < 30) {
						$status = 1;
					} elseif ($result >= 30 && $result < 45) {
						$status = 2;
					} elseif ($result >= 45 && $result < 60) {
						$status = 3;
					} elseif ($result >= 60) {
						$status = 4;
					}
					break;


				case (3):
					if ($result >= 10 && $result < 20) {
						$status = 0;
					} elseif ($result >= 20 && $result < 35) {
						$status = 1;
					} elseif ($result >= 35 && $result < 50) {
						$status = 2;
					} elseif ($result >= 50 && $result < 70) {
						$status = 3;
					} elseif ($result >= 70) {
						$status = 4;
					}
					break;

				case (4):
					if ($result < 20) {
						$status = 0;
					} elseif ($result >= 20 && $result < 30) {
						$status = 1;
					} elseif ($result >= 30 && $result < 45) {
						$status = 2;
					} elseif ($result >= 45 && $result < 60) {
						$status = 3;
					} elseif ($result >= 60) {
						$status = 4;
					}
					break;

				case (5):
					if ($result >= 10 && $result < 20) {
						$status = 0;
					} elseif ($result >= 20 && $result < 25) {
						$status = 1;
					} elseif ($result >= 25 && $result < 35) {
						$status = 2;
					} elseif ($result >= 35 && $result < 40) {
						$status = 3;
					} elseif ($result >= 40) {
						$status = 4;
					}
					break;

				case (6):
					if ($result >= 10 && $result < 20) {
						$status = 0;
					} elseif ($result >= 20 && $result < 30) {
						$status = 1;
					} elseif ($result >= 30 && $result < 35) {
						$status = 3;
					} elseif ($result >= 35) {
						$status = 4;
					}
					break;
			}
		} elseif ($sexo == 1) {

			switch ($exercise) {

				case (1):
					if ($result >= 20 && $result < 30) {
						$status = 0;
					} elseif ($result >= 30 && $result < 45) {
						$status = 1;
					} elseif ($result >= 45 && $result < 60) {
						$status = 2;
					} elseif ($result >= 60 && $result < 85) {
						$status = 3;
					} elseif ($result >= 85) {
						$status = 4;
					}
					break;


				case (2):
					if ($result < 45) {
						$status = 0;
					} elseif ($result >= 45 && $result < 60) {
						$status = 1;
					} elseif ($result >= 60 && $result < 85) {
						$status = 2;
					} elseif ($result >= 85 && $result < 100) {
						$status = 3;
					} elseif ($result >= 100) {
						$status = 4;
					}
					break;


				case (3):
					if ($result >= 15 && $result < 25) {
						$status = 0;
					} elseif ($result >= 25 && $result < 40) {
						$status = 1;
					} elseif ($result >= 40 && $result < 60) {
						$status = 2;
					} elseif ($result >= 60 && $result < 90) {
						$status = 3;
					} elseif ($result >= 90) {
						$status = 4;
					}
					break;

				case (4):
					if ($result < 30) {
						$status = 0;
					} elseif ($result >= 30 && $result < 45) {
						$status = 1;
					} elseif ($result >= 45 && $result < 60) {
						$status = 2;
					} elseif ($result >= 60 && $result < 85) {
						$status = 3;
					} elseif ($result >= 85) {
						$status = 4;
					}
					break;

				case (5):
					if ($result >= 20 && $result < 25) {
						$status = 0;
					} elseif ($result >= 25 && $result < 35) {
						$status = 1;
					} elseif ($result >= 35 && $result < 45) {
						$status = 2;
					} elseif ($result >= 45 && $result < 50) {
						$status = 3;
					} elseif ($result >= 50) {
						$status = 4;
					}
					break;

				case (6):
					if ($result >= 20 && $result < 30) {
						$status = 0;
					} elseif ($result >= 30 && $result < 50) {
						$status = 1;
					} elseif ($result >= 50 && $result < 70) {
						$status = 2;
					} elseif ($result >= 70 && $result < 90) {
						$status = 3;
					} elseif ($result >= 90) {
						$status = 4;
					}
					break;
			}
		}

		return $status;
	}

	public function grease(Request $request)
	{
		$request->validate(['grease' => 'required']);
		$user = User::find($request->user()->id);
		try {
			$grease = new Grease_record($request->all());
			$grease->user_id = $request->user()->id;
			$grease->save();
			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}

	public function update(Request $request)
	{
		try {
			$user = User::find($request->user()->id);
			$user->fill($request->all());
			$user->save();
			return response()->json(['message' => 'Success'], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}

	public function progress(Request $request)
	{
		try {
			$user = User::find($request->user()->id);
			$medidas = measurement_record::where('user_id', '=', $request->user()->id)->get();
			foreach ($medidas as $medida) {
				$medida->imc = $medida->weight / (($medida->stature / 100) * ($medida->stature / 100));
				$medida->perimetro_cintura = $medidas->min_waist;
				$medida->ica = $medida->min_waist / $user->stature;
			}
			$food_measures = Food_measures::where('user_id', '=', $user->id)->get();
			return response()->json(['Progress' => $medidas, 'Progress_food' => $food_measures], 200);
		} catch (Exception $e) {
			return response()->json(['message' => 'error'], 500);
		}
	}
}
