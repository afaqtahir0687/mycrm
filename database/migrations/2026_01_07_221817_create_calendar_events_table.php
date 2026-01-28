<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->string('event_type'); // meeting, call, reminder, task, deadline
            $table->morphs('related'); // related_type, related_id
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('attendees')->nullable(); // Array of user IDs or email addresses
            $table->string('location')->nullable();
            $table->boolean('is_all_day')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, yearly
            $table->boolean('send_reminder')->default(true);
            $table->integer('reminder_minutes')->default(15);
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->timestamps();
            
            $table->index(['start_time', 'end_time']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
