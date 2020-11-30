<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtaRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ota_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('device_id');
            $table->integer('type');
            $table->integer('type_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            $table->foreign('device_id')
            ->references('id')
            ->on('devices');

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ota_records');
    }
}
