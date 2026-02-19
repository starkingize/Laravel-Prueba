<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Listar todos los productos.
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Obtener un producto por ID.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|integer|exists:currencies,id',
            'tax_cost' => 'nullable|numeric|min:0',
            'manufacturing_cost' => 'nullable|numeric|min:0',
        ]);

        $product = Product::create($validated);
        return response()->json($product);
    }

    /**
     * Actualizar un producto.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|integer|exists:currencies,id',
            'tax_cost' => 'nullable|numeric|min:0',
            'manufacturing_cost' => 'nullable|numeric|min:0',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    /**
     * Obtener los precios de un producto.
     */
    public function prices(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $prices = ProductPrice::where('product_id', $id)->get();
        return response()->json($prices);
    }

    /**
     * Crear un nuevo precio para un producto.
     */
    public function storePrice(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        $priceExists = ProductPrice::where('product_id', $id)->where('currency_id', $request->currency_id)->first();
        if ($priceExists) {
            return response()->json(['message' => 'Price already exists'], 400);
        }

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'currency_id' => 'required|integer|exists:currencies,id',
            'price' => 'required|numeric|min:0',
        ]);

        $price = ProductPrice::create([
            'product_id' => $id,
            'currency_id' => $validated['currency_id'],
            'price' => $validated['price'],
        ]);

        return response()->json($price);
    }
}
