<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cs2_match_maps', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('match_id')->constrained('cs2_matches')->cascadeOnDelete();
            $table->string('source');
            $table->string('external_id');
            $table->unsignedTinyInteger('order')->default(1);
            $table->string('map_name')->nullable();
            $table->foreignId('winner_team_id')->nullable()->constrained('cs2_teams')->nullOnDelete();
            $table->unsignedTinyInteger('team_one_score')->nullable();
            $table->unsignedTinyInteger('team_two_score')->nullable();
            $table->string('status')->default('scheduled')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs2_match_maps');
    }
};
