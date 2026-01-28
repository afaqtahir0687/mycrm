<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->integer('ai_score')->nullable()->after('lead_score');
            $table->text('ai_insights')->nullable()->after('ai_score');
            $table->json('ai_recommendations')->nullable()->after('ai_insights');
            $table->string('sentiment')->nullable()->after('ai_recommendations');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['ai_score', 'ai_insights', 'ai_recommendations', 'sentiment']);
        });
    }
};
