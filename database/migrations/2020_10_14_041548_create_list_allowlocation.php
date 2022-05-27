<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListAllowlocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maping_userloc', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('office_id');
            $table->double('lat');
            $table->double('long');
            $table->string('name_location');
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
        Schema::dropIfExists('list_allowlocation');
    }
}
