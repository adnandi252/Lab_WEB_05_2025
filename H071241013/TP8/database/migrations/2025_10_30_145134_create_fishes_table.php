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
        Schema::create('fishes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->enum('rarity', ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'])->nullable(false);
            $table->decimal('base_weight_min', 8, 2)->nullable(false);
            $table->decimal('base_weight_max', 8, 2)->nullable(false);
            $table->integer('sell_price_per_kg')->nullable(false);
            $table->decimal('catch_probability', 5, 2)->nullable(false);
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fishes');
    }
};
