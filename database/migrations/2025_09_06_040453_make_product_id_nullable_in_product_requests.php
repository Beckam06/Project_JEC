<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

return new class extends Migration
{
    /**
     * WHY: La columna product_id actualmente NO permite NULL, 
     * pero para productos nuevos necesitamos que sea NULL
     */
    public function up()
    {
        // WHY: Primero debemos eliminar la constraint de clave foránea
        // porque no podemos modificar una columna que tiene foreign key
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // WHY: Ahora podemos cambiar la columna para que acepte NULL
        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreignId('product_id')
                  ->nullable() // ← ESTO permite valores NULL
                  ->change();
        });

        // WHY: Volvemos a crear la foreign key constraint pero ahora permite NULL
        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * WHY: En caso de revertir la migración, volvemos a cómo estaba antes
     */
    public function down()
    {
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreignId('product_id')
                  ->nullable(false) // ← Vuelve a NO permitir NULL
                  ->change();
        });

        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }
};