<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->unique();
            $table->string('service_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->decimal('hourly_rate', 15, 2)->default(0);
            $table->decimal('fixed_price', 15, 2)->nullable();
            $table->string('pricing_type')->default('hourly'); // hourly, fixed, custom
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('active');
            $table->text('service_details')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
