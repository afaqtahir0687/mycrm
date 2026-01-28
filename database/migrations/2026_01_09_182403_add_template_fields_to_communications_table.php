<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('assigned_lead_id')->constrained('email_templates')->onDelete('set null');
            $table->string('communication_category')->nullable()->after('template_id'); // email, sms, letter, call, visit
            $table->string('attachment_path')->nullable()->after('communication_category'); // For visit reports, letters, etc.
            $table->text('visit_report')->nullable()->after('attachment_path'); // For visit reports
        });
    }

    public function down(): void
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn(['template_id', 'communication_category', 'attachment_path', 'visit_report']);
        });
    }
};
