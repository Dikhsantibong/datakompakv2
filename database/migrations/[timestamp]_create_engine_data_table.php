<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('engine_data', function (Blueprint $table) {
            $table->id();
            $table->integer('machine_id')->unsigned();  // Sesuaikan dengan tipe int di tabel machines
            $table->date('date');
            $table->time('time');
            $table->decimal('kw', 10, 2)->nullable();
            $table->decimal('kvar', 10, 2)->nullable();
            $table->decimal('cos_phi', 5, 4)->nullable();
            $table->timestamps();

            $table->foreign('machine_id')
                  ->references('id')
                  ->on('machines')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('engine_data');
    }
}; 