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
            $table->string('status', 30)->default('pendiente');
      
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->integer('quantity_approved')->nullable(); // Cantidad aprobada
            $table->integer('quantity_pending')->nullable();  // Cantidad pendiente
            $table->text('notes')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_requests');
    }
};