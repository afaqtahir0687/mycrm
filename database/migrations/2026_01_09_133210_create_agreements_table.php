<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_number')->unique();
            $table->string('agreement_type')->default('STC'); // STC, SLA, Agreement Draft
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->onDelete('set null');
            $table->foreignId('deal_id')->nullable()->constrained('deals')->onDelete('set null');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');
            $table->date('agreement_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('draft'); // draft, sent, signed, active, expired, terminated
            $table->text('terms_conditions')->nullable();
            $table->text('sla_terms')->nullable(); // For SLA type agreements
            $table->text('deliverables')->nullable();
            $table->decimal('total_value', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('agreement_file_path')->nullable();
            $table->string('signed_file_path')->nullable();
            $table->date('signed_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
