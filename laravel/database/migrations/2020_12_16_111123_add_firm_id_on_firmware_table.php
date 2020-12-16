<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmIdOnFirmwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firmware', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('version');

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
        Schema::table('firmware', function (Blueprint $table) {
            $table->dropForeign('firmware_firm_id_foreign');
            $table->dropColumn('firm_id');
        });
    }
}
