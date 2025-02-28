<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJsiColumnToDailySummariesTable extends Migration
{
    public function up()
    {
        Schema::table('daily_summaries', function (Blueprint $table) {
            $table->decimal('jsi', 10, 2)->nullable()->after('nof');
        });
    }

    public function down()
    {
        Schema::table('daily_summaries', function (Blueprint $table) {
            $table->dropColumn('jsi');
        });
    }
} 