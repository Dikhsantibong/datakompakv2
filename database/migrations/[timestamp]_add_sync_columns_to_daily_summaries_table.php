<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSyncColumnsToDailySummariesTable extends Migration
{
    public function up()
    {
        Schema::table('daily_summaries', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->string('unit_source')->nullable()->after('power_plant_id');
            $table->index(['uuid', 'unit_source']);
        });
    }

    public function down()
    {
        Schema::table('daily_summaries', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'unit_source']);
        });
    }
} 