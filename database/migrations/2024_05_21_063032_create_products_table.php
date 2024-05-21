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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->string('name', 50);
            $table->bigInteger('price');
            $table->integer('stock');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
