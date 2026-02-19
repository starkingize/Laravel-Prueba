<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Registros de ejemplo: divisas, productos y precios.
     *
     * @return void
     */
    public function run(): void
    {
        // array con todas las divisas
        $currencies = [
            ['name' => 'Dólar estadounidense', 'symbol' => 'USD', 'exchange_rate' => 1.000000],
            ['name' => 'Euro', 'symbol' => 'EUR', 'exchange_rate' => 0.920000],
            ['name' => 'Peso dominicano', 'symbol' => 'DOP', 'exchange_rate' => 58.500000],
            ['name' => 'Peso mexicano', 'symbol' => 'MXN', 'exchange_rate' => 17.250000],
        ];


        // creamos y mantenemos el dataset de divisas
        $created = [];
        foreach ($currencies as $data) {
            $created[$data['symbol']] = Currency::firstOrCreate(
                ['symbol' => $data['symbol']],
                $data
            );
        }

        // array con todos los productos
        $products = [
            [
                'name' => 'Laptop Pro 15',
                'description' => 'Portátil 15 pulgadas, 16GB RAM, 512GB SSD.',
                'price' => 999.00,
                'currency_id' => $created['USD']->id,
                'tax_cost' => 80.00,
                'manufacturing_cost' => 450.00,
            ],
            [
                'name' => 'Monitor 27" 4K',
                'description' => 'Monitor IPS 27 pulgadas, resolución 4K UHD.',
                'price' => 349.00,
                'currency_id' => $created['USD']->id,
                'tax_cost' => 28.00,
                'manufacturing_cost' => 180.00,
            ],
            [
                'name' => 'Teclado mecánico',
                'description' => 'Teclado mecánico inalámbrico, switches RGB.',
                'price' => 89.00,
                'currency_id' => $created['EUR']->id,
                'tax_cost' => 15.00,
                'manufacturing_cost' => 35.00,
            ],
        ];

        // creamos y mantenemos el dataset de productos
        $productModels = [];
        foreach ($products as $data) {
            $productModels[] = Product::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        // Precios en otras divisas (product_id, currency_symbol, price en esa divisa)
        $prices = [
            [0, 'EUR', 919.08],   // Laptop en EUR
            [0, 'MXN', 17232.75], // Laptop en MXN
            [1, 'EUR', 320.08],   // Monitor en EUR
            [1, 'MXN', 6020.25], // Monitor en MXN
            [2, 'USD', 96.80],    // Teclado en USD
            [2, 'MXN', 1670.00],  // Teclado en MXN
        ];


        // creamos y mantenemos el dataset de precios de productos
        foreach ($prices as [$index, $symbol, $price]) {
            $product = $productModels[$index];
            $currency = $created[$symbol];
            ProductPrice::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'currency_id' => $currency->id,
                ],
                ['price' => $price] // precio del producto en la divisa
            );
        }
    }
}
