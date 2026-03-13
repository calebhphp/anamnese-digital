<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anamneses', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique()->index();
            $table->json('responses')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index('completed');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('anamneses');
    }
};