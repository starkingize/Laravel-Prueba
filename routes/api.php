<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product; // modelo de producto
use App\Models\ProductPrice; // modelo de precio de producto
use App\Http\Controllers\Api\AuthController; // controlador de autenticación

/*
|--------------------------------------------------------------------------
| API Routes: /api/login, /api/register, /api/logout, /api/user, /api/products, /api/products/{id}, /api/products/{id}/prices
|--------------------------------------------------------------------------
*/

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas (requieren token Bearer)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Productos (solo con autenticación)
    Route::get('/products', function () {
        $products = Product::all();
        return response()->json($products);
    });

    // Obtener un producto por ID
    Route::get('/products/{id}', function (int $id) {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    })->where('id', '[0-9]+');

    // Obtener los precios de un producto
    Route::get('/products/{id}/prices', function (int $id) {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $prices = ProductPrice::where('product_id', $id)->get();
        return response()->json($prices);
    })->where('id', '[0-9]+');

    // Crear un nuevo producto
    Route::post('/products', function (Request $request) {
        // Primero se validan los campos requeridos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|integer|exists:currencies,id',
            'tax_cost' => 'nullable|numeric|min:0',
            'manufacturing_cost' => 'nullable|numeric|min:0',
        ]);

        // Luego se inserta el producto utilizando los datos validados
        $product = Product::create($validated);

        return response()->json($product);
        if (!$product) {
            return response()->json(['message' => 'Product not created'], 400);
        }
    })->where('id', '[0-9]+');

    // Actualizar un producto
    Route::put('/products/{id}', function (Request $request, int $id) {
        $product = Product::find($id); // Buscamos el producto por ID
        // Validamos los campos requeridos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|integer|exists:currencies,id',
            'tax_cost' => 'nullable|numeric|min:0',
            'manufacturing_cost' => 'nullable|numeric|min:0',
        ]);
        $product->update($validated);

        // si el producto no se pudo actualizar
        if (!$product) return response()->json(['message' => 'Product not updated'], 400);

        // si se actualizo correctamente
        return response()->json($product);
    })->where('id', '[0-9]+');

    // Eliminar un producto
    Route::delete('/products/{id}', function (int $id) {
        $product = Product::find($id); // Buscamos el producto por ID
        // Verificamos si el producto existe
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        // eliminamos el producto
        $product->delete();

        // si el producto no se pudo eliminar
        if (!$product) return response()->json(['message' => 'Product not deleted'], 400);

        return response()->json(['message' => 'Product deleted']);
    })->where('id', '[0-9]+');

    // Crear un nuevo precio para un producto
    Route::post('/products/{id}/prices', function (Request $request, int $id) {
        $product = Product::find($id);

        // si el producto no existe
        if (!$product)
            return response()->json(['message' => 'Product not found'], 404);

        // validamos los campos requeridos
        $validated = $request->validate([
            'currency_id' => 'required|integer|exists:currencies,id',
            'price' => 'required|numeric|min:0',
        ]);

        // insertamos los campos manualmente ya que el id viene por query
        $price = ProductPrice::create(['product_id' => $id, 'currency_id' => $validated['currency_id'], 'price' => $validated['price']]); // creamos el precio

        // si el precio no se pudo crear
        if (!$price) return response()->json(['message' => 'Price not created'], 400);

        // si se creo correctamente
        return response()->json($price);
    })->where('id', '[0-9]+');
});
