<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Home_test;
use App\Food;
use App\Food_measures;
use App\Notfood;
use App\measurement_record;
use App\Promotions_food;
use App\Menu;
use App\Menu_food;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            $user = User::find($request->user()->id);

            $food = Food::where('class', '=', $user->feeding_type)->get();
            return response()->json(['Alimentos' => $food], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate(['feeding_type' => 'required']);
        try {
            $user = User::find($request->user()->id);
            $user->feeding_type = $request->feeding_type;
            $user->save();
            return response()->json(['message' => 'Success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function cal_menu(Request $request)
    {

        try {
            $user = User::find($request->user()->id);
            $test = Home_test::where('user_id', '=', $user->id)->orderBy('id', 'DESC')->first();
            if (is_null($test)) {
                return response()->json(['message' => 'Este usuario no tiene Test'], 200);
            }
            $edad = 0;
            $tmb = 0;
            $tmba = 0;
            $strategy = 0;
            //calculamos la edad media
            if ($user->age == 0) {
                $edad = 20;
            } elseif ($user->age == 1) {
                $edad = 30;
            } elseif ($user->age == 2) {
                $edad = 40;
            } elseif ($user->age == 3) {
                $edad = 50;
            } elseif ($user->age == 4) {
                $edad = 60;
            } elseif ($user->age == 5) {
                $edad = 70;
            }

            //calculamos tmb
            if ($user->gender == 0) {
                //Mujer
                $tmb = (9.5 * $user->weight) + (1.8 * $user->stature) - (4.6 * $edad) + 655;
            } elseif ($user->gender == 1) {
                //Hombre
                $tmb = (13.7 * $user->weight) + (5 * $user->stature) - (6.7 * $edad) + 66.4;
            }

            //calculamos tmba
            if ($user->act_physical == 0) {
                $tmba = $tmb * 1.2;
            } elseif ($user->act_physical == 1) {
                $tmba = $tmb * 1.35;
            } elseif ($user->act_physical == 2) {
                $tmba = $tmb * 1.45;
            } elseif ($user->act_physical == 3) {
                $tmba = $tmb * 1.6;
            } elseif ($user->act_physical == 4) {
                $tmba = $tmb * 1.7;
            }

            //Consultamos Objetivo de la persona 
            if ($user->objective == 0) {
                //tonificacion
                $grasas = $tmba * 0.30;
                $proteinas = $tmba * 0.25;
                $carbo = $tmba * 0.45;
            } elseif ($user->objective == 1) {
                //hipertrofia   
                if ($test->level < 2) {
                    $tmba = $tmba + ($tmba * 0.20);
                    $grasas = $tmba * 0.25;
                    $proteinas = $tmba * 0.20;
                    $carbo = $tmba * 0.55;
                    $strategy = 20;
                } else {
                    $tmba = $tmba + ($tmba * 0.25);
                    $grasas = $tmba * 0.25;
                    $proteinas = $tmba * 0.20;
                    $carbo = $tmba * 0.55;
                    $strategy = 25;
                }
            } elseif ($user->objective == 2) {
                //perdida de grasa
                if ($test->level < 2) {
                    $tmba = $tmba - ($tmba * 0.20);
                    $grasas = $tmba * 0.35;
                    $proteinas = $tmba * 0.30;
                    $carbo = $tmba * 0.35;
                    $strategy = -20;
                } else {
                    $tmba = $tmba - ($tmba * 0.25);
                    $grasas = $tmba * 0.35;
                    $proteinas = $tmba * 0.30;
                    $carbo = $tmba * 0.35;
                    $strategy = -25;
                }
            }

            // Guardamos las medidas de carbo, proteina y grasas 
            $food_measures = new Food_measures();
            $food_measures->user_id = $user->id;
            $food_measures->total_carbo = $carbo / 4;
            $food_measures->total_protein = $proteinas / 4;
            $food_measures->total_greases = $grasas / 9;
            $food_measures->tmb = $tmb;
            $food_measures->tmba = $tmba;
            $food_measures->strategy_n = $strategy;
            $food_measures->save();

            // Guardamos las medidas de carbo, proteina y grasas por comida
            //proteinas y grasas partes iguales
            // carbo 35, 25, 20, 20 
            $promotions_food = new Promotions_food();
            $promotions_food->user_id = $user->id;
            $promotions_food->type_food = 0; //desayuno
            $promotions_food->grease = ($grasas * 0.25) / 9;
            $promotions_food->carbo = ($carbo * 0.30) / 4; //
            $promotions_food->protein = ($proteinas * 0.25) / 4;
            $promotions_food->save();

            $promotions_food = new Promotions_food();
            $promotions_food->user_id = $user->id;
            $promotions_food->type_food = 1; //snack
            $promotions_food->grease = ($grasas * 0.25) / 9;
            $promotions_food->carbo = ($carbo * 0.20) / 4;
            $promotions_food->protein = ($proteinas * 0.25) / 4;
            $promotions_food->save();

            $promotions_food = new Promotions_food();
            $promotions_food->user_id = $user->id;
            $promotions_food->type_food = 2; //almuerzo
            $promotions_food->grease = ($grasas * 0.25) / 9;
            $promotions_food->carbo = ($carbo * 0.30) / 4;
            $promotions_food->protein = ($proteinas * 0.25) / 4;
            $promotions_food->save();

            $promotions_food = new Promotions_food();
            $promotions_food->user_id = $user->id;
            $promotions_food->type_food = 3; //cena
            $promotions_food->grease = ($grasas * 0.25) / 9;
            $promotions_food->carbo = ($carbo * 0.20) / 4;
            $promotions_food->protein = ($proteinas * 0.25) / 4;
            $promotions_food->save();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function Notfoods(Request $request)
    {
        $request->validate(['foods' => 'required']);
        try {
            $user = User::find($request->user()->id);
            for ($i = 0; $i < count($request->foods); $i++) {
                $notfood = new Notfood();
                $notfood->user_id = $user->id;
                $notfood->foods_id = $request->foods[$i];
                $notfood->save();
            }

            return response()->json(['message' => 'Success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function menu(Request $request)
    {
        $request->validate(['type_food' => 'required']);
        try {
            $user = User::find($request->user()->id);
            $promotions_food = Promotions_food::where('user_id', '=', $user->id)
                ->where('type_food', '=', $request->type_food)
                ->orderBy('id', 'DESC')->first();

            if (is_null($promotions_food)) {
                return response()->json(['message' => 'Este usuario no tiene Menu Asignado'], 200);
            } else {

                $notfood = Notfood::select('foods_id')->where('user_id', '=', $user->id)->get()->toArray();

                if ($request->type_food == 0) {
                    $available_food = Food::where('class', '=', $user->feeding_type)
                        ->where('breakfast', '=', 1)->whereNotIn('id', $notfood)->get();
                } elseif ($request->type_food == 1) {
                    $available_food = Food::where('class', '=', $user->feeding_type)
                        ->where('snack', '=', 1)->whereNotIn('id', $notfood)->get();
                } elseif ($request->type_food == 2) {
                    $available_food = Food::where('class', '=', $user->feeding_type)
                        ->where('lunch', '=', 1)->whereNotIn('id', $notfood)->get();
                } elseif ($request->type_food == 3) {
                    $available_food = Food::where('class', '=', $user->feeding_type)
                        ->where('dinner', '=', 1)->whereNotIn('id', $notfood)->get();
                } else {
                    return response()->json(['message' => 'tipo de comida no validad'], 300);
                }

                return response()->json(['Menu' => $promotions_food, 'Foods' => $available_food], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function store_menu(Request $request)
    {
        $request->validate([
            'type_food' => 'required', 'total_proteins' => 'required', 'total_greases' => 'required',
            'total_carbos' => 'required', 'total_calories' => 'required',
            'day' => 'required',
        ]);
        try {
            $check = Menu::where('day', '=', $request->day)->where('type_food', '=', $request->type_food)
                ->where('user_id', '=', $request->user()->id)->first();
            if (!is_null($check)) {
                return response()->json(['message' => 'Este usuario ya tiene Menu Asignado para esta comida'], 200);
            } else {
                $menu = new Menu($request->all());
                $menu->day = $request->day;
                $menu->user_id = $request->user()->id;
                $menu->save();
                //guardando las comidas del menu
                for ($i = 0; $i < count($request->foods); $i++) {
                    $menufood = new Menu_food();
                    $menufood->menu_id = $menu->id;
                    $menufood->food_id = $request->foods[$i][0];
                    $menufood->quantity = $request->foods[$i][1];
                    $menufood->measure = $request->foods[$i][2];
                    $menufood->save();
                }
                return response()->json(['message' => 'success'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }


    public function resume_food(Request $request)
    {
        try {
            $user = User::find($request->user()->id);
            $food_measures = Food_measures::where('user_id', '=', $user->id)->orderBy('id', 'DESC')->first();
            $menu = Menu::where('user_id', '=', $user->id)->where('day', '=', date('Y-m-d'))->get();

            return response()->json([
                'Calories que dederia consumir al dia' => $food_measures,
                'calorias que ha consumido en el dia' => $menu
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function indicators(Request $request)
    {
        try {
            $user = User::find($request->user()->id);
            $medidas = measurement_record::where('user_id', '=', $user->id)->orderBy('id', 'DESC')->first();
            $food_measures = Food_measures::where('user_id', '=', $user->id)->orderBy('id', 'DESC')->first();
            if (is_null($medidas)) {
                $perimetro_cintura = NUll;
            } else {
                $perimetro_cintura = $medidas->min_waist;
            }
            if (is_null($food_measures)) {
                $tmb = NUll;
                $strategy = NUll;
            } else {
                $tmb = $food_measures->tmb;
                $strategy = $food_measures->strategy_n;
            }
            return response()->json([
                'imc' => $user->imc, 'ica' => $user->ica,
                'tmb' => $tmb, 'Estrategia_N' => $strategy,
                'Perimetro_Cintura' => $perimetro_cintura
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function food_ready(Request $request)
    {
        $request->validate(['type_food' => 'required', 'day' => 'required']);
        try {
            $menu = Menu::where('user_id', '=', $request->user()->id)
                ->where('type_food', '=', $request->type_food)
                ->where('day', '=', $request->day)
                ->orderBy('id', 'DESC')->with('menu_food', 'menu_food.food')->first();
            return response()->json(['menu' => $menu], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function update_menu(Request $request)
    {
        $request->validate(['menu_id' => 'required']);
        try {
            $menu = Menu::find($request->menu_id);
            if (is_null($menu)) {
                return response()->json(['message' => 'Menu No Existe'], 202);
            }
            $menu->fill($request->all());
            $menu->save();
            //eliminando las comidas del menu      
            Menu_food::Where('menu_id', '=', $request->menu_id)->delete();
            //guardando las comidas del menu
            for ($i = 0; $i < count($request->foods); $i++) {
                $menufood = new Menu_food();
                $menufood->menu_id = $menu->id;
                $menufood->food_id = $request->foods[$i][0];
                $menufood->quantity = $request->foods[$i][1];
                $menufood->measure = $request->foods[$i][2];
                $menufood->save();
            }
            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }


    public function delete_menu(Request $request)
    {
        $request->validate(['menu_id' => 'required']);
        try {
            Menu::Where('id', '=', $request->menu_id)->delete();
            Menu_food::Where('menu_id', '=', $request->menu_id)->delete();
            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'error'], 500);
        }
    }
}
