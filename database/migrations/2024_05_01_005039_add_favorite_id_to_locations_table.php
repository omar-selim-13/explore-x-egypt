<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->unsignedBigInteger('favorite_id')->nullable();
            $table->foreign('favorite_id')->references('id')->on('favorites')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['favorite_id']);
            $table->dropColumn('favorite_id');
        });
    }
};
