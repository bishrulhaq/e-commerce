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

            // Basic product info
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade');

            // Description
            $table->text('description')->nullable();
            $table->decimal('cost_price', 8, 2)->default(0.00);
            $table->decimal('regular_price', 8, 2)->default(0.00);
            $table->decimal('sale_price', 8, 2)->nullable();
            $table->timestamp('sale_start')->nullable();
            $table->timestamp('sale_end')->nullable();

            // Stock
            $table->integer('stock')->default(0);

            // Timestamps
            $table->timestamps();
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
