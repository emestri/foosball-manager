<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropForeign(['game_id']);

            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropForeign(['game_id']);

            $table->foreign('game_id')
                ->references('id')->on('games');
        });
    }
};
