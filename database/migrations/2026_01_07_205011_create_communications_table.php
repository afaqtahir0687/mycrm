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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['email', 'phone', 'sms', 'whatsapp', 'meeting', 'note'])->default('email');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
            $table->string('from_email')->nullable();
            $table->string('to_email')->nullable();
            $table->string('from_phone')->nullable();
            $table->string('to_phone')->nullable();
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('duration_minutes')->nullable();
            $table->enum('status', ['sent', 'delivered', 'read', 'failed', 'pending'])->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
