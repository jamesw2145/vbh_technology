<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasureHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measure_hdr', function (Blueprint $table) {
            $table->increments('measure_hdr_uid');
            $table->decimal('entry_id', 18, 0);
            $table->string('doc_no', 20);
            $table->dateTime('production_date')->nullable();
            $table->string('technician', 20);
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
        Schema::dropIfExists('measure.hdr');
    }
}
