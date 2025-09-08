<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Eliminar la foreign key temporalmente
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // 2. Cambiar la columna para que acepte NULL
        Schema::table('product_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });

        // 3. Volver a agregar la foreign key
        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        // 1. Eliminar la foreign key
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // 2. Cambiar la columna para que NO acepte NULL
        Schema::table('product_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });

        // 3. Volver a agregar la foreign key
        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};