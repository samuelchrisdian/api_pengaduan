<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pengaduan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->bigIncrements('id_pengaduan');
            $table->date('tgl_pengaduan');
            $table->unsignedBigInteger('id_user');
            $table->text('isi_laporan');
            $table->string('foto');
            $table->enum('status', ['terkirim', 'proses', 'selesai', 'tolak']);
            $table->unsignedBigInteger('id_kategori');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengaduan');
    }
}
