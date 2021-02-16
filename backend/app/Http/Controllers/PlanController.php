<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Plan;
use App\User_plan;

class PlanController extends Controller
{
    public function index()
    {
        $planes = Plan::orderBy('duration', 'ASC')->get();
        return response()->json(['Planes' => $planes], 200);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'plan_id'    => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'payment_id' => 'required',
            'date' => 'required',
        ]);
        try {
            $user_plan = new User_plan($request->all());
            $user_plan->user_id = $request->user()->id;
            $user_plan->save();

            return response()->json(['message' => 'Success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
