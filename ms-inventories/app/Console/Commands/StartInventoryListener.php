<?php

namespace App\Console\Commands;

use App\Inventories\Infrastructure\Messaging\InventoryRequestListener;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

/**
 * Listener class to listen for inventory stock requests
 * Class StartInventoryListener
 * @package App\Console\Commands
 */
class StartInventoryListener extends Command
{
    protected $signature = 'inventory:listen';

    protected $description = 'Start the inventory listener to listen for ingredient requests';

    protected $inventoryRequestListener;

    public function __construct(InventoryRequestListener $inventoryRequestListener)
    {
        parent::__construct();
        $this->inventoryRequestListener = $inventoryRequestListener;
    }
    /**
     * Listen messages from the message broker queue
     */
    public function handle()
    {
        try {
            // Initialize the queue
            Amqp::publish('routing-key-1', json_encode([
                'action' => 'init'  
            ]), [
                'queue' => 'inventory_request_queue',
                'exchange' => 'exchange_requests',  
                'exchange_type' => 'direct'        
            ]);
            $this->info("Start listening to inventory_request_queue");
            Amqp::consume('inventory_request_queue', function ($message, $resolver) {
                $data = json_decode($message->body, true);
                if(isset($data['action'])&& $data['action'] == 'init') {
                    $this->info("Initialized listener for inventory_request_queue");
                    $resolver->acknowledge($message);
                    return;
                }
                $this->info("Received message from inventory_request_queue: " . $message->body);
                $this->inventoryRequestListener->listen($message);
                $resolver->acknowledge($message);
            },['persistent' => true]);
        } catch (\Exception $e) {
            $this->error("Listener error: " . $e->getMessage());
        }
    }
}
