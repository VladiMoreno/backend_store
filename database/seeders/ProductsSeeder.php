<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(16, 16) as $index) {
            $product = new Products();
            $product->name = "Producto $index";
            $product->price = rand(10, 100);
            $barcodeNumber = $this->generateBarcodeNumber();
            $barcodeImage = $this->generateBarcodeImage($barcodeNumber);
            $barcodePath = 'barcodes/' . $barcodeNumber . '.png';
            Storage::put('public/' . $barcodePath, $barcodeImage);
            $product->barcode = $barcodeNumber;
            $product->barcode_image_path = $barcodePath;
            $product->save();
        }
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
}
