<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_kit_bbm_storage_tanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_kit_bbm_id')->constrained('laporan_kit_bbm')->onDelete('cascade');
            $table->integer('tank_number');
            $table->decimal('cm', 10, 2)->nullable();
            $table->decimal('liter', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_kit_bbm_storage_tanks');
    }
}; 