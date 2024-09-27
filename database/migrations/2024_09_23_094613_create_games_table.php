<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('guest_team_id')->constrained('teams');
            $table->enum('mode', ['Single', 'BestOfThree', 'BestOfFive'])->index();
            $table->enum('winner', ['home', 'guest'])->nullable()->index();
            $table->timestamp('kickoff_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
