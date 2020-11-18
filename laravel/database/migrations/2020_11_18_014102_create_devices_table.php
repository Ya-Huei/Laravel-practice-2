<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_no');
            $table->integer('status');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('firm_id')->nullable();
            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations');
            
            $table->foreign('firm_id')
                ->references('id')
                ->on('firms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
