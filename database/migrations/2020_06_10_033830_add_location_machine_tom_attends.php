<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationMachineTomAttends extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('m_attends', function($table) {
            $table->string('type_data')->default(NULL);
            $table->string('noted')->default(NULL);
            $table->integer('status_employee')->default(NULL);
            $table->integer('machine_id')->default(NULL);
            $table->string('lat_attend')->default(NULL);
            $table->string('lon_attend')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
