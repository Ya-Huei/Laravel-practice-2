<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable()->after('remember_token');
            $table->unsignedBigInteger('firm_id')->nullable()->after('location_id');

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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_location_id_foreign');
            $table->dropForeign('users_firm_id_foreign');
            $table->dropColumn('location_id');
            $table->dropColumn('firm_id');
        });
    }
}
