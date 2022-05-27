<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDayOff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days_off', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('departement');
            $table->string('supervisor');
            $table->string('replacement_pic');
            $table->text('reason');
            $table->text('submitted_job');
            $table->date('days_off_date');
            $table->integer('total_days');
            $table->integer('remaining_days_off');
            $table->integer('days_off_balance');
            $table->integer('status');
            $table->integer('user_id');
            $table->date('back_to_office');
            $table->string('phone_number');
            $table->timestamp('response_date');
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
        //
    }
}
