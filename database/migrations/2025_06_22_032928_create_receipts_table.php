<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('receipts', function (Blueprint $table) {
        $table->id();
        $table->decimal('subtotal', 8, 2);
        $table->decimal('tax', 8, 2);
        $table->decimal('discount', 8, 2);
        $table->decimal('total', 8, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};
