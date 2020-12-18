<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\Ally;
use Mail;

class ProductController extends Controller
{
    public function index()
    {
        $ally = Ally::with('product')->get();
        return response()->json(['Productos' => $ally], 200);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'product_id'       => 'required',
        ]);
        $product = Product::find($request->product_id);
        $user = $request->user();
        try {
            Mail::send('mails/payProduct', ['user' => $user, 'product' => $product], function ($msg) use ($user, $product) {
                $msg->to('info@fittech247.com')->subject('Notificación de compra');
            });
            return response()->json(['message' => 'Success'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
