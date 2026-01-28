<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->text('engagement_outcome')->nullable()->after('content');
            $table->enum('engagement_status', ['pending', 'completed', 'overdue'])->default('pending')->after('engagement_outcome');
            $table->dateTime('engagement_date')->nullable()->after('engagement_status');
            $table->foreignId('assigned_lead_id')->nullable()->constrained('leads')->onDelete('set null')->after('lead_id');
        });
    }

    public function down(): void
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->dropForeign(['assigned_lead_id']);
            $table->dropColumn(['engagement_outcome', 'engagement_status', 'engagement_date', 'assigned_lead_id']);
        });
    }
};
