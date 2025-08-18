<?php

namespace App\Providers;

use App\Purchases\Domain\Events\PurchaseCompleted;
use App\Purchases\Domain\Repositories\PurchaseRepositoryInterface;
use App\Purchases\Infrastructure\Messaging\SendPurchaseCompletedToRabbitMQ;
use App\Purchases\Infrastructure\Persistence\EloquentPurchaseRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(PurchaseRepositoryInterface::class, EloquentPurchaseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            PurchaseCompleted::class,
            SendPurchaseCompletedToRabbitMQ::class,
        ); 
    }
}
