<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('quantity');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('restrict');
            $table->index(['draw_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
