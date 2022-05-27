<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMLoggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_loggings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mprof_id')->nullable();
            $table->string('name', 100);
            $table->dateTime('log_time', 0);
            $table->time('sunrise', 0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('m_loggings');
    }
}
