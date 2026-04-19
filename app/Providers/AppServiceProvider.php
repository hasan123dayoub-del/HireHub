<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProposalRepositoryInterface;
use App\Repositories\Eloquent\EloquentProposalRepository;
use App\Events\ProposalAccepted;
use App\Listeners\SendProposalAcceptedNotification;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\ProjectRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentProjectRepository::class
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
