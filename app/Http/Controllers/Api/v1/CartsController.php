<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Carts;
use App\Http\Controllers\Controller;
use App\Models\DetailCarts;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Carts::with(['user', 'DetailCart'])->get()->map(function ($cart) {
            $total = $cart->DetailCart->sum(function ($detail) {
                return $detail->amount * $detail->price;
            });

            return [
                'id' => $cart->id,
                'total' => $total,
                'created_at' => $cart->created_at,
                'user_name' => $cart->user->name
            ];
        });

        return response()->json($carts, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_user' => 'required|integer|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.amount' => 'required|integer|min:1',
        ], [
            'id_user.required' => 'El campo id_user es obligatorio.',
            'id_user.integer' => 'El campo id_user debe ser un número entero.',
            'id_user.exists' => 'El campo id_user debe existir en la tabla users.',
            'products.required' => 'El campo products es obligatorio.',
            'products.array' => 'El campo products debe ser un array.',
            'products.min' => 'El campo products debe tener al menos un producto.',
            'products.*.product_id.required' => 'El campo product_id es obligatorio.',
            'products.*.product_id.integer' => 'El campo product_id debe ser un número entero.',
            'products.*.product_id.exists' => 'El campo product_id debe existir en la tabla products.',
            'products.*.amount.required' => 'El campo amount es obligatorio.',
            'products.*.amount.integer' => 'El campo amount debe ser un número entero.',
            'products.*.amount.min' => 'El campo amount debe ser al menos 1.',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()->all()], 422);
        }

        $user = User::find($request->input('id_user'));

        if (empty($user)) {
            return response()->json([
                "error" => "Usuario inexistente"
            ], 404);
        }

        $cart = new Carts();

        $cart->id_user = $request->input('id_user');
        $cart->save();

        foreach ($request->input('products') as $product) {
            $detailProduct = Products::find($product['product_id']);

            $subtotal = $detailProduct['price'] *  $product['amount'];

            $detailCart = new DetailCarts();

            $detailCart->cart_id = $cart->id;
            $detailCart->product_id = $product['product_id'];
            $detailCart->amount = $product['amount'];
            $detailCart->price = $subtotal;

            $detailCart->save();
        }

        return response()->json(['message' => 'Compra realizada.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Carts $carts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carts $carts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carts $carts)
    {
        //
    }
}
