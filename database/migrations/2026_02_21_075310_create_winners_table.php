<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id');
            $table->foreignId('prize_id');
            $table->foreignId('participant_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('restrict');
            $table->foreign('prize_id')->references('id')->on('prizes')->onDelete('restrict');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('restrict');
            $table->index(['draw_id']);
            $table->index(['prize_id']);
            $table->index(['participant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};
