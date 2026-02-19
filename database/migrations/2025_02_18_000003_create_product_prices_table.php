<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // creamos la tabla de precios de productos
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete(); // clave foranea a la tabla de productos
            $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete(); // clave foranea a la tabla de divisas
            $table->decimal('price', 15, 2);
            $table->timestamps();

            $table->unique(['product_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_prices');
    }
};
