<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cs2_stage_teams', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('stage_id')->constrained('cs2_stages')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('cs2_teams')->cascadeOnDelete();
            $table->string('source');
            $table->string('external_id');
            $table->unsignedTinyInteger('wins')->default(0);
            $table->unsignedTinyInteger('losses')->default(0);
            $table->string('record')->default('0-0')->index();
            $table->string('status')->default('active')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['stage_id', 'team_id']);
            $table->unique(['source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs2_stage_teams');
    }
};
