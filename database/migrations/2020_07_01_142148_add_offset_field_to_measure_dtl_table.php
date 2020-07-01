<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOffsetFieldToMeasureDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measure_dtl', function (Blueprint $table) {
            $table->decimal('offset', 18, 3)->nullable()->after('fitting_2_crimp_len');
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
            $table->dropColumn('offset');
        });
    }
}
