<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveQtrProducedFieldToMeasureHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measure_hdr', function (Blueprint $table) {
            //
            $table->integer('qty_produced')->nullable();
        });
        Schema::table('measure_dtl', function (Blueprint $table) {
            //
            $table->dropColumn('qty_produced');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('measure_dtl', function (Blueprint $table) {
            //
            $table->integer('qty_produced')->nullable();
        });
        Schema::table('measure_hdr', function (Blueprint $table) {
            //
            $table->dropColumn('qty_produced');
        });
    }
}
