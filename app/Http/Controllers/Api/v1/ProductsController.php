<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Products;
use App\Http\Controllers\Controller;
use App\Models\DetailCarts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::select('name', 'price', 'barcode_image_path')->get();

        // Transformar la colección para incluir la URL completa de la imagen
        $products->transform(function ($product) {
            $product->barcode_image_path = Storage::url($product->barcode_image_path);
            return $product;
        });

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio del producto es obligatorio.',
            'price.numeric' => 'El precio debe ser numérico.',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
        $barcodeNumber = $this->generateBarcodeNumber();

        $barcodeImage = $this->generateBarcodeImage($barcodeNumber);

        $barcodePath = 'barcodes/' . $barcodeNumber . '.png';
        Storage::put('public/' . $barcodePath, $barcodeImage);

        // Crear el producto
        $product = new Products();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->barcode = $barcodeNumber;
        $product->barcode_image_path = $barcodePath;
        $product->save();

        return response()->json($product, 201);
    }

    private function generateBarcodeNumber()
    {
        return rand(1000000000, 9999999999);
    }

    private function generateBarcodeImage($barcodeNumber)
    {
        $generator = new BarcodeGeneratorPNG();
        return $generator->getBarcode($barcodeNumber, $generator::TYPE_CODE_128);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $product = Products::find($id);

        if (!empty($product)) {
            return response()->json($product, 200);
        } else {
            return response()->json([
                "message" => "El Registro no se encuentra."
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!empty($product)) {
            // Verificar si el producto está en detail_carts
            $detailCart = DetailCarts::where('product_id', $id)->exists();

            if ($detailCart) {
                return response()->json([
                    "message" => "No se puede actualizar el producto porque está presente en detalle de carrito."
                ], 400);
            }

            $validation = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ], [
                'name.required' => 'El nombre del producto es obligatorio.',
                'price.required' => 'El precio del producto es obligatorio.',
                'price.numeric' => 'El precio debe ser numérico.',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors()->all();
                return response()->json(['errors' => $errors], 422);
            }
            $product->name = $request->input('name');
            $product->price = $request->input('price');

            $product->update();

            return response()->json($product, 200);
        } else {
            return response()->json([
                "message" => "El Registro no se encuentra."
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!empty($product)) {
            $detailCart = DetailCarts::where('product_id', $id)->exists();

            if ($detailCart) {
                return response()->json([
                    "message" => "No se puede actualizar el producto porque está presente en detalle de carrito."
                ], 400);
            }

            $product->delete();

            return response()->json($product, 200);
        } else {
            return response()->json([
                "message" => "El Registro no se encuentra."
            ], 404);
        }
    }
}
