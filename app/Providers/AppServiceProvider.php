<?php

namespace App\Providers;

use App\Repositories\Cs2\Contracts\EventRepositoryInterface;
use App\Repositories\Cs2\Contracts\MatchMapRepositoryInterface;
use App\Repositories\Cs2\Contracts\MatchRepositoryInterface;
use App\Repositories\Cs2\Contracts\StageRepositoryInterface;
use App\Repositories\Cs2\Contracts\StageTeamRepositoryInterface;
use App\Repositories\Cs2\Contracts\TeamRepositoryInterface;
use App\Repositories\Cs2\Eloquent\EventRepository;
use App\Repositories\Cs2\Eloquent\MatchMapRepository;
use App\Repositories\Cs2\Eloquent\MatchRepository;
use App\Repositories\Cs2\Eloquent\StageRepository;
use App\Repositories\Cs2\Eloquent\StageTeamRepository;
use App\Repositories\Cs2\Eloquent\TeamRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(StageRepositoryInterface::class, StageRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(StageTeamRepositoryInterface::class, StageTeamRepository::class);
        $this->app->bind(MatchRepositoryInterface::class, MatchRepository::class);
        $this->app->bind(MatchMapRepositoryInterface::class, MatchMapRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
