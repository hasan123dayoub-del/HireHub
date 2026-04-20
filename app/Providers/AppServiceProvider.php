<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProposalRepositoryInterface;
use App\Repositories\Eloquent\EloquentProposalRepository;
use App\Events\ProposalAccepted;
use App\Listeners\SendProposalAcceptedNotification;
use Illuminate\Support\Facades\Event;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Eloquent\EloquentProjectRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->bind(
            ProjectRepositoryInterface::class,
            EloquentProjectRepository::class
        );

        $this->app->bind(
            ProposalRepositoryInterface::class,
            EloquentProposalRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            ProposalAccepted::class,
            SendProposalAcceptedNotification::class
        );
    }
}
