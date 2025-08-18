<?php

namespace App\Providers;

use App\Inventories\Application\Listeners\UpdateInventoryListener;
use App\Inventories\Domain\Events\StockReplenishedEvent;
use App\Inventories\Domain\Repositories\InventoryRepositoryInterface;
use App\Inventories\Infrastructure\Persistence\InventoryRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Event::listen(
            StockReplenishedEvent::class,
            UpdateInventoryListener::class,
        ); 
    }
}
