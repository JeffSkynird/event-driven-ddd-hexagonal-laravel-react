<?php

namespace App\Providers;

use App\Kitchen\Domain\Events\DishPrepared;
use App\Kitchen\Domain\Repositories\DishRepositoryInterface;
use App\Kitchen\Domain\Repositories\OrderRepositoryInterface;
use App\Kitchen\Infrastructure\Messaging\PublishDishPreparedToQueue;
use App\Kitchen\Infrastructure\Persistence\EloquentDishRepository;
use App\Kitchen\Infrastructure\Persistence\EloquentOrderRepository;
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
        $this->app->bind(DishRepositoryInterface::class, EloquentDishRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            DishPrepared::class,
            PublishDishPreparedToQueue::class,
        ); 
    }
}
