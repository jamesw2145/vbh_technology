<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoseMeasuredLenFieldToMeasureDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measure_dtl', function (Blueprint $table) {
            //
            $table->decimal('hose_measured_len', 18, 4)->after('hose_date_code');
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
            $table->dropColumn('hose_measured_len');
        });
    }
}
