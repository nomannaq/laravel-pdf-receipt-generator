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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('email_template_id')->nullable()->constrained('templates')->onDelete('set null');
            $table->foreignId('sms_template_id')->nullable()->constrained('templates')->onDelete('set null');
            $table->foreignId('pdf_template_id')->nullable()->constrained('templates')->onDelete('set null');
            $table->json('side_effects')->nullable(); // Store side effect configurations
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
