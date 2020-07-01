<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasureDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measure_dtl', function (Blueprint $table) {
            $table->increments('measure_dtl_uid');
            $table->decimal('entry_id', 18, 0);

            $table->string('item_id', 40);
            $table->string('measure_uom', 15);
            $table->char('measure_type', 10);
            $table->string('hose_item_id', 40)->nullable();
            $table->string('hose_date_code', 20);

            $table->integer('qty_produced')->nullable();
            $table->string('fitting_1_item_id', 40)->nullable();
            $table->decimal('fitting_1_crimp_od', 18, 6)->nullable();
            $table->decimal('fitting_1_crimp_len', 18, 6)->nullable();

            $table->string('fitting_2_item_id', 40)->nullable();
            $table->decimal('fitting_2_crimp_od', 18, 6)->nullable();
            $table->decimal('fitting_2_crimp_len', 18, 6)->nullable();

            $table->dateTime('date_created')->nullable();
            $table->string('created_by', 20);
            $table->char('edit_flag', 1)->nullable();
            $table->dateTime('date_last_modified')->nullable();
            $table->string('last_modified_by', 20)->nullable();
            $table->char('delete_flag', 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measure_dtl');
    }
}
