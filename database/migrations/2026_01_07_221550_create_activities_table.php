<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_type'); // created, updated, deleted, called, emailed, met, note
            $table->string('title');
            $table->text('description')->nullable();
            $table->morphs('subject'); // subject_type, subject_id (polymorphic) - automatically creates index
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('activity_date');
            $table->integer('duration_minutes')->nullable();
            $table->string('location')->nullable();
            $table->json('metadata')->nullable(); // Additional data in JSON format
            $table->timestamps();
            
            $table->index('activity_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
