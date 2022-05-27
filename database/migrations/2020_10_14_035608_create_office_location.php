<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_location', function (Blueprint $table) {
            $table->id();
            $table->string('name_office');
            $table->double('lat');
            $table->double('long');
            $table->string('address');
            $table->integer('status')->default(1);
            $table->integer('radius_allow');
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
        Schema::dropIfExists('office_location');
    }
}
