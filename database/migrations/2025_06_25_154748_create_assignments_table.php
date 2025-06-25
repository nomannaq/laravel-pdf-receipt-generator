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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('creditor_name');
            $table->string('creditor_contact_person_name')->nullable();
            $table->string('creditor_contact_person_email')->nullable();
            $table->string('creditor_contact_person_phone')->nullable();
            $table->string('debtor_name');
            $table->string('debtor_contact_person_name')->nullable();
            $table->decimal('balance_capital', 15, 2)->default(0);
            $table->decimal('balance_penalty_interest', 15, 2)->default(0);
            $table->decimal('balance_debt_collection_fee', 15, 2)->default(0);
            $table->decimal('balance_overpayment', 15, 2)->default(0);
            $table->decimal('penalty_interest_rate', 8, 4)->default(0);
            $table->decimal('total_for_due_date', 15, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
