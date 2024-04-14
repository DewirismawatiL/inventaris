<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void //up untuk mengubah modifikasi menambahkan atau yang berhubungan dengan elemen elemen database
    {
        Schema::create('lendings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("stuff_id");
            $table->dateTime("date_time");
            $table->string("name");
            $table->bigInteger("user_id");
            $table->text("notes");
            $table->integer("total_stuff");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void //down memerintah untuk menghapus di table nya
    {
        Schema::dropIfExists('lendings'); //dropIfExists itu artinya ada
    }
};
