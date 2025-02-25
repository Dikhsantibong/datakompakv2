<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rencana_daya_mampu', function (Blueprint $table) {
            $table->id();
            $table->integer('machine_id')->unsigned();
            $table->date('tanggal');
            $table->decimal('rencana', 10, 2)->nullable();
            $table->decimal('realisasi', 10, 2)->nullable();
            $table->decimal('daya_pjbtl_silm', 10, 2)->nullable();
            $table->decimal('dmp_existing', 10, 2)->nullable();
            $table->string('unit_source', 100);
            $table->timestamps();

            $table->index('tanggal');
            $table->index('unit_source');
            
            $table->foreign('machine_id')
                  ->references('id')
                  ->on('machines')
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rencana_daya_mampu');
    }
}; 