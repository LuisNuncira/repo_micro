<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('retro_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sprint_id')->constrained()->onDelete('cascade');
    $table->enum('categoria', ['accion', 'logro', 'impedimento', 'comentario', 'otro']);
    $table->text('descripcion');
    $table->boolean('cumplida')->nullable();
    $table->date('fecha_revision')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retro_items');
    }
};
