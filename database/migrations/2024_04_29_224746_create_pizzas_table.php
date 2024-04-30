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
        Schema::create('pizzas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('ingredient_pizza', function (Blueprint $table) {
            $table->foreignId('pizza_id')->constrained();
            $table->foreignId('ingredient_id')->constrained();
            $table->primary(['pizza_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_pizza');
        Schema::dropIfExists('pizzas');
    }
};
