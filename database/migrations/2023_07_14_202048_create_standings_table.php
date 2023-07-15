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
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('team_id');
            $table->integer('term');
            $table->integer('played')->nullable()->default(0);
            $table->integer('wins')->nullable()->default(0);
            $table->integer('draws')->nullable()->default(0);
            $table->integer('losses')->nullable()->default(0);
            $table->integer('goals_for')->nullable()->default(0);
            $table->integer('goals_against')->nullable()->default(0);
            $table->integer('goal_difference')->nullable()->default(0);
            $table->integer('points')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues');
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
