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

        $data = [
            "statusCode" => 200,
            "message" => "Compras encontrados",
            "data" => $carts,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_user' => 'required|integer|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.amount' => 'required|integer|min:1',
        ], [
            'id_user.required' => 'El campo id_user es obligatorio.',
            'id_user.integer' => 'El campo id_user debe ser un número entero.',
            'id_user.exists' => 'El campo id_user debe existir en la tabla users.',
            'products.required' => 'El campo products es obligatorio.',
            'products.array' => 'El campo products debe ser un array.',
            'products.min' => 'El campo products debe tener al menos un producto.',
            'products.*.id.required' => 'El campo id es obligatorio.',
            'products.*.id.integer' => 'El campo id debe ser un número entero.',
            'products.*.id.exists' => 'El campo id debe existir en la tabla products.',
            'products.*.amount.required' => 'El campo amount es obligatorio.',
            'products.*.amount.integer' => 'El campo amount debe ser un número entero.',
            'products.*.amount.min' => 'El campo amount debe ser al menos 1.',
        ]);

        if ($validation->fails()) {
            $data = [
                "statusCode" => 422,
                "message" => "Errores en los parametros",
                "errors" => $validation->errors()->all()
            ];

            return response()->json($data, 422);
        }

        $user = User::find($request->input('id_user'));

        if (empty($user)) {
            $data = [
                "statusCode" => 404,
                "message" => "Ha ocurrido un error",
                "error" => "El usuario no se encuentra.",
            ];

            return response()->json($data, 404);
        }

        $cart = new Carts();

        $cart->id_user = $request->input('id_user');
        $cart->save();

        foreach ($request->input('products') as $product) {
            $detailProduct = Products::find($product['id']);

            $subtotal = $detailProduct['price'] *  $product['amount'];

            $detailCart = new DetailCarts();

            $detailCart->cart_id = $cart->id;
            $detailCart->product_id = $product['id'];
            $detailCart->amount = $product['amount'];
            $detailCart->price = $subtotal;

            $detailCart->save();
        }

        $data = [
            "statusCode" => 201,
            "message" => "Compra realizada exitosamente !",
            "data" => [],
        ];

        return response()->json($data, 201);
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
