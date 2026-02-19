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
        // creamos la tabla de divisas
        Schema::create('currencies', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 100); //
            $table->string('symbol', 10);
            $table->decimal('exchange_rate', 15, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
