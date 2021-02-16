<?php

use Illuminate\Http\Request;



Route::group(['prefix' => 'auth'], function () {

	Route::post('login', 'AuthController@login');
	Route::post('register', 'AuthController@register');
	Route::post('email-verify', 'UserController@verify_email');
	Route::post('reset-password', 'AuthController@reset_password');
	Route::get('/config-cache', function () {
		$exitCode = Artisan::call('config:cache');
		return '<h1>Clear Config cleared</h1>';
	});


	Route::group(['middleware' => 'auth:api'], function () {
		Route::get('logout', 'AuthController@logout');
		Route::get('user', 'AuthController@user');
		Route::post('changepassword', 'AuthController@changepassword');

		Route::post('update-client', 'UserController@update');

		Route::post('heart_rate', 'UserController@heart_rate');
		Route::post('family_medical_record', 'UserController@family_medical_record');
		Route::post('medical_record', 'UserController@medical_record');
		Route::post('grease_record', 'UserController@grease');
		//Route::post('cc', 'UserController@cintura_cadera');
		Route::post('update_weight', 'UserController@update_weight');
		Route::post('measurement_record', 'UserController@measurement_record'); //cintura ccadera
		Route::post('aerobic_test', 'UserController@aerobic_test');
		Route::post('power_test', 'UserController@power_test');
		//Route::get('calcular', 'ExerciseController@calculate_routine');
		Route::get('routine', 'ExerciseController@routine');
		Route::get('routine-random/{u}/{r}/{e}/{n}', 'ExerciseController@exerciseRandom');
		Route::get('exercise-available/{u}/{e}/{n}', 'ExerciseController@exerciseAvailable');
		Route::get('update-exercise/{r}/{ev}/{en}', 'ExerciseController@updateExercise');
		Route::post('update-routine', 'ExerciseController@updateRoutine');
		//Route::get('show-routine/{usuario_id}/{n}', 'ExerciseController@daily_routine');

		Route::post('home-test', 'ExerciseHomeController@home_test');
		Route::get('routine-home', 'ExerciseHomeController@routine');
		Route::post('update-routine-home', 'ExerciseHomeController@updateRoutine');

		Route::get('exercise-home-available/{usuario}/{ejercicio}/{n}', 'ExerciseHomeController@exerciseHomeAvailable');
		Route::get('update-exercise-home/{r}/{ev}/{en}', 'ExerciseHomeController@updateExerciseHome');

		Route::post('exercise-routine', 'ExerciseHomeController@Exce_routine');
		Route::get('exercise-heating', 'ExerciseHomeController@Exce_heating');

		//Alimentacion 
		Route::post('update-type-food', 'FoodController@update');
		Route::post('foods', 'FoodController@show');
		Route::post('foods-not-like', 'FoodController@Notfoods');

		Route::post('calculate_menu', 'FoodController@cal_menu');
		Route::post('menu', 'FoodController@menu');
		Route::post('store-menu', 'FoodController@store_menu');
		Route::post('update-menu', 'FoodController@update_menu');
		Route::post('delete-menu', 'FoodController@delete_menu');
		Route::post('resume-food', 'FoodController@resume_food');
		Route::post('indicators', 'FoodController@indicators');
		Route::post('ready-food', 'FoodController@food_ready');

		//Planes y Pagos
		Route::get('planes', 'PlanController@index');
		Route::post('pay-plan', 'PlanController@pay');

		//Historial de Operaciones
		Route::post('personal_history', 'AuthController@personal_history');
		Route::post('progress', 'UserController@progress');
		//Route::get('exercise/{r}/{ei}/{e}/{o}', 'ExerciseHomeController@exercise');
		Route::post('stretching', 'ExerciseHomeController@stretching');

		//Tienda Fittech
		Route::post('products', 'ProductController@index');
		Route::post('pay-products', 'ProductController@pay');
	});
});
