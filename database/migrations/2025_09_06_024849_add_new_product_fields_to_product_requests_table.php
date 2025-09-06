<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('product_requests', function (Blueprint $table) {
        $table->boolean('is_new_product')->default(false)->after('status');
        $table->string('new_product_name')->nullable()->after('is_new_product');
        $table->text('new_product_description')->nullable()->after('new_product_name');
    });
}

public function down()
{
    Schema::table('product_requests', function (Blueprint $table) {
        $table->dropColumn(['is_new_product', 'new_product_name', 'new_product_description']);
    });
}
};
