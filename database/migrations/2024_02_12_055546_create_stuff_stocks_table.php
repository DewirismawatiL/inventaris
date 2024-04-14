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
        Schema::create('stuff_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("stuff_id");
            // $table->bigInteger("barang_id"); jika pk sama fk nya tidak sama
            $table->integer("total_avaible");//untuk forezenqy yang primaryqy nya id auto increments
            $table->integer("total_defec");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stuff_stocks');
    }
};