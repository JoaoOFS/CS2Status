<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cs2_matches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->constrained('cs2_events')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('cs2_stages')->cascadeOnDelete();
            $table->foreignId('team_one_id')->nullable()->constrained('cs2_teams')->nullOnDelete();
            $table->foreignId('team_two_id')->nullable()->constrained('cs2_teams')->nullOnDelete();
            $table->foreignId('winner_team_id')->nullable()->constrained('cs2_teams')->nullOnDelete();
            $table->string('source');
            $table->string('external_id');
            $table->unsignedTinyInteger('round')->nullable()->index();
            $table->string('record_group')->nullable()->index();
            $table->string('best_of')->default('bo1');
            $table->string('status')->default('scheduled')->index();
            $table->dateTime('starts_at')->nullable()->index();
            $table->unsignedTinyInteger('team_one_score')->nullable();
            $table->unsignedTinyInteger('team_two_score')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs2_matches');
    }
};
