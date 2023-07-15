<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('home_id');
            $table->unsignedBigInteger('away_id');
            $table->date('date');
            $table->unsignedSmallInteger('term');
            $table->unsignedSmallInteger('week');
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->decimal('home_rate')->nullable();
            $table->decimal('away_rate')->nullable();
            $table->unsignedBigInteger('winner')->nullable();
            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues');
            $table->foreign('home_id')->references('id')->on('teams');
            $table->foreign('away_id')->references('id')->on('teams');
            $table->foreign('winner')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
