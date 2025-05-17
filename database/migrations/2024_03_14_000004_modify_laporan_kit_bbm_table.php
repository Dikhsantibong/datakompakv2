<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_kit_bbm', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'storage_tank_1_cm',
                'storage_tank_1_liter',
                'storage_tank_2_cm',
                'storage_tank_2_liter',
                'service_tank_1_liter',
                'service_tank_1_percentage',
                'service_tank_2_liter',
                'service_tank_2_percentage',
                'flowmeter_1_awal',
                'flowmeter_1_akhir',
                'flowmeter_1_pakai',
                'flowmeter_2_awal',
                'flowmeter_2_akhir',
                'flowmeter_2_pakai'
            ]);

            // Add service_total_stok column
            $table->decimal('service_total_stok', 10, 2)->nullable()->after('total_stok');
        });
    }

    public function down()
    {
        Schema::table('laporan_kit_bbm', function (Blueprint $table) {
            // Restore old columns
            $table->decimal('storage_tank_1_cm', 10, 2)->nullable();
            $table->decimal('storage_tank_1_liter', 10, 2)->nullable();
            $table->decimal('storage_tank_2_cm', 10, 2)->nullable();
            $table->decimal('storage_tank_2_liter', 10, 2)->nullable();
            $table->decimal('service_tank_1_liter', 10, 2)->nullable();
            $table->decimal('service_tank_1_percentage', 5, 2)->nullable();
            $table->decimal('service_tank_2_liter', 10, 2)->nullable();
            $table->decimal('service_tank_2_percentage', 5, 2)->nullable();
            $table->decimal('flowmeter_1_awal', 10, 2)->nullable();
            $table->decimal('flowmeter_1_akhir', 10, 2)->nullable();
            $table->decimal('flowmeter_1_pakai', 10, 2)->nullable();
            $table->decimal('flowmeter_2_awal', 10, 2)->nullable();
            $table->decimal('flowmeter_2_akhir', 10, 2)->nullable();
            $table->decimal('flowmeter_2_pakai', 10, 2)->nullable();

            // Drop new column
            $table->dropColumn('service_total_stok');
        });
    }
}; 