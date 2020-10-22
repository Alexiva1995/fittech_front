<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Medical_record;
use App\User_plan;
use App\Personal_history;
use Mail;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|unique:users',
            'password' => 'required|string',
            'gender' => 'required|string',
            'age' => 'required',
            'weight' => 'required',
            'stature' => 'required',
            'objective' => 'required',
            //'act_physical' => 'required', 
            'training_experience' => 'required',
            'training_place' => 'required',
        ]);

        try {
            $user = new User($request->all());
            $user->password = bcrypt($request->password);

            switch ($request->age) {
                case (0):
                    $user->fcmin = 138;
                    $user->fcmax = 168;
                    break;

                case (1):
                    $user->fcmin = 133;
                    $user->fcmax = 162;
                    break;

                case (2):
                    $user->fcmin = 126;
                    $user->fcmax = 153;
                    break;

                case (3):
                    $user->fcmin = 119;
                    $user->fcmax = 145;
                    break;

                case (4):
                    $user->fcmin = 112;
                    $user->fcmax = 136;
                    break;

                case (5):
                    $user->fcmin = 105;
                    $user->fcmax = 128;
                    break;
            }

            $user->imc = $request->weight / (($request->stature / 100) * ($request->stature / 100));
            if ($user->imc >= 19 && $user->imc < 25) {
                $user->indicator_imc = "normal";
            } elseif ($user->imc >= 25 && $user->imc < 30) {
                $user->indicator_imc = "sobre peso";
            } elseif ($user->imc >= 30 && $user->imc < 36) {
                $user->indicator_imc = "obesidad";
            }

            $user->save();

            $Medical_record = new Medical_record($request->all());
            $Medical_record->user_id = $user->id;

            $Medical_record->save();

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();

            Mail::send('mails/welcome', ['user' => $user], function ($msg) use ($user) {
                $msg->to($user->email)->subject('Bienvenido');
            });


            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at
                )
                    ->toDateTimeString(),
                'user' => $user,
                'home_test' => NULL,
                'power_test' => NULL,
                'aerobic_test' => NULL,
                'routine_ready_week' => 0,
                'food_measures' => NULL,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function login(Request $request)
    {

        $request->validate([
            'email'       => 'required|string',
            'password'    => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        $home_test = DB::table('home_test')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();

        $power_test = DB::table('power_test')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();

        $aerobic_test = DB::table('aerobic_test')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();
        $fecha = carbon::now();
        $monday = $fecha->startOfWeek();
        $routine = DB::table('routine_home')
            ->where('user_id', '=', $request->user()->id)
            ->where('ready', '=', 1)
            ->where('created_at', '>=', $monday)
            ->count();

        $food_measures = DB::table('food_measures')
            ->where('user_id', '=', $request->user()->id)
            ->orderBy('id', 'DESC')
            ->first();

        $plan = DB::table('user_plans')
            ->join('plans', 'user_plans.plan_id', '=', 'plans.id')
            ->where('user_id', '=', $request->user()->id)
            ->select('plans.duration', 'user_plans.*')
            ->orderBy('id', 'DESC')
            ->first();
        if (!is_null($plan)) {
            $fechaActual = Carbon::now();
            $fechaPlan = new Carbon($plan->date);
            $diasPlan = $fechaActual->diffInDays($fechaPlan);
            $diasRestantes = $plan->duration - $diasPlan;
        } else {
            $diasRestantes = Null;
        }


        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )
                ->toDateTimeString(),
            'user' => $user,
            'home_test' => $home_test,
            'power_test' => $power_test,
            'aerobic_test' => $aerobic_test,
            'routine_ready_week' => $routine,
            'food_measures' => $food_measures,
            'dias_restantes_plan' => $diasRestantes,
        ]);
    }



    public function check(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->with('Medical_record')->first();
    }




    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
        'Successfully logged out']);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        $measurement_record = DB::table('measurement_record')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();

        $home_test = DB::table('home_test')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();

        $power_test = DB::table('power_test')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();

        $aerobic_test = DB::table('aerobic_test')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->first();

        $fecha = carbon::now();
        $monday = $fecha->startOfWeek();
        $routine = DB::table('routine_home')
            ->where('user_id', '=', $request->user()->id)
            ->where('ready', '=', 1)
            ->where('created_at', '>=', $monday)
            ->count();

        $food_measures = DB::table('food_measures')
            ->where('user_id', '=', $request->user()->id)
            ->orderBy('id', 'DESC')
            ->first();
        $plan = DB::table('user_plans')
            ->join('plans', 'user_plans.plan_id', '=', 'plans.id')
            ->where('user_id', '=', $request->user()->id)
            ->select('plans.duration', 'user_plans.*')
            ->orderBy('id', 'DESC')
            ->first();

        $fechaActual = Carbon::now();
        $fechaPlan = new Carbon($plan->date);
        $diasPlan = $fechaActual->diffInDays($fechaPlan);
        $diasRestantes = $plan->duration - $diasPlan;

        return response()->json([
            'user' => $user,
            'measurement_record' => $measurement_record,
            'home_test' => $home_test,
            'power_test' => $power_test,
            'aerobic_test' => $aerobic_test,
            'routine_ready_week' => $routine,
            'food_measures' => $food_measures,
            'dias_restantes_plan' => $diasRestantes,
        ]);
    }


    public function changepassword(Request $request)
    {
        $request->validate(['password' => 'required']);
        try {
            $user = User::find($request->user()->id);
            $user->password = bcrypt($request->password);
            $user->save();
            $request->user()->token()->revoke();
            Mail::send('mails/changePassword', ['user' => $user], function ($msg) use ($user) {
                $msg->to($user->email)->subject('Cambio de clave');
            });
            return response()->json(['message' => 'Success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function reset_password(Request $request)
    {
        $request->validate(['email' => 'required']);
        try {
            $usuario = User::where('email', '=', $request->email)->first();
            if (!is_null($usuario)) {
                $usuario->password = bcrypt("ftt-" . $usuario->id);
                $usuario->save();
                Mail::send('mails/resetPassword', ['user' => $usuario], function ($msg) use ($usuario) {
                    $msg->to($usuario->email)->subject('Recuperar Clave');
                });
                return response()->json(['message' => 'Success'], 200);
            } else {
                return response()->json(['message' => 'Email No encontrado'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function personal_history(Request $request)
    {
        $request->validate(['description' => 'required']);
        try {
            $personal_history = new Personal_history($request->all());
            $personal_history->user_id = $request->user()->id;
            $personal_history->save();
            return response()->json(['message' => 'Success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }
}
