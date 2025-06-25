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
        Schema::table('templates', function (Blueprint $table) {
            $table->text('email_subject')->nullable()->after('content');
            $table->json('pdf_attachments')->nullable()->after('email_subject'); // Store PDF template IDs for email attachments
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['email_subject', 'pdf_attachments']);
        });
    }
};
