<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmtablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmtables', function (Blueprint $table) {
            $table->id();
            $table->string('day', 20);
            $table->string('type', 20);
            $table->time('start_at')->nullable();
            $table->time('end_at')->nullable();
            $table->tinyInteger('role')->default(1);
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
        Schema::dropIfExists('tmtables');
    }
}
