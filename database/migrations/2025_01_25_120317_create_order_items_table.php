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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade'); // Deletes order_items when an order is deleted

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade'); // Deletes order_items when a product is deleted

            $table->integer('quantity')->default(0);
            $table->decimal('price', 8, 2)->default(0.0);
            $table->decimal('subtotal', 10, 2)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
