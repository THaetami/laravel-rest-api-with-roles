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
        Schema::create('claim_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('reward_id');
            $table->timestamp('claim_date');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');;
            $table->foreign('reward_id')->references('id')->on('rewards')->onDelete('cascade');;
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_rewards');
    }
};
