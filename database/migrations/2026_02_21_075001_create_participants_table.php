<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id');
            $table->string('name');
            $table->string('store_code');
            $table->string('store_name');
            $table->string('invoice_number');
            $table->string('store_address');
            $table->boolean('is_winner')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('restrict');
            $table->unique(['invoice_number', 'draw_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
