<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->string('unit')->default('pcs');
            $table->integer('stock_quantity')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('active');
            $table->text('specifications')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
