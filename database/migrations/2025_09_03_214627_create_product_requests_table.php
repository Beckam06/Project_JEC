<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity_requested');
            $table->enum('receptor', ['Casa Amarilla', 'Casa Naranja', 'Casa Verde']);
            $table->string('requester_name');
            $table->text('purpose')->nullable();
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_requests');
    }
};