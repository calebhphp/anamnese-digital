<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anamnese_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anamnese_id')
                  ->constrained()
                  ->onDelete('cascade'); // Se deletar a anamnese, deleta o histórico
            $table->json('responses');
            $table->timestamps();
            
            // Índices
            $table->index('anamnese_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('anamnese_histories');
    }
};