<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMAttendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_attends', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('mprof_id')->nullable();
            $table->foreignId('tmtable_id')->nullable();
            $table->time('in_time');
            $table->time('in_tolerance_time');
            $table->time('out_time');
            $table->time('over_time');
            $table->time('late_time');
            $table->time('first_attend');
            $table->time('last_attend');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_attends');
    }
}
