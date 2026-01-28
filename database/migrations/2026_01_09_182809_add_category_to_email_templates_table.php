<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('category')->default('email')->after('type'); // email, sms, letter, call_script, visit_report
            $table->string('file_path')->nullable()->after('category'); // For document templates (PDF, DOCX)
            $table->boolean('is_official')->default(false)->after('is_active'); // Official letter/message templates
        });
    }

    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['category', 'file_path', 'is_official']);
        });
    }
};
