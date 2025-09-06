<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->enum('receptor', ['Casa Amarilla', 'Casa Naranja', 'Casa Verde'])->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropColumn('receptor');
        });
    }
};