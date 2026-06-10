<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cs2_stages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->constrained('cs2_events')->cascadeOnDelete();
            $table->string('source');
            $table->string('external_id');
            $table->string('name');
            $table->string('slug')->nullable()->index();
            $table->string('format')->default('swiss')->index();
            $table->string('status')->default('scheduled')->index();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs2_stages');
    }
};
