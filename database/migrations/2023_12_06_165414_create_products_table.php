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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock_quantity');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('processor')->nullable();
            $table->integer('ram_size')->nullable();
            $table->string('storage')->nullable();
            $table->string('graphics_card')->nullable();
            $table->string('operating_system')->nullable();
            $table->timestamps();
            $table->json('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
