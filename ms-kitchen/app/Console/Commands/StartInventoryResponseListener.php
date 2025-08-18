<?php

namespace App\Console\Commands;

use App\Kitchen\Infrastructure\Messaging\InventoryResponseListener;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

/**
 * Listener class to listen for inventory restock responses
 * Class InventoryReadyListener
 * @package App\Console\Commands
 */
class StartInventoryResponseListener extends Command
{
    protected $signature = 'inventory-response:listen';

    protected $description = 'Start the inventory listener to listen for restock requests';

    protected $listener;

    public function __construct(InventoryResponseListener $listener)
    {
        parent::__construct();
        $this->listener = $listener;
    }
    /**
     * Listen messages from the message broker queue
     */
    public function handle()
    {
        try {
            // Initialize the queue
            Amqp::publish('routing-key-2', json_encode([
                'action' => 'init'  
            ]), [
                'queue' => 'inventory_response_queue',
                'exchange' => 'exchange_responses', 
                'exchange_type' => 'direct'
            ]);

            $this->info("Start listening to inventory_response_queue");
            Amqp::consume('inventory_response_queue', function ($message, $resolver) {
                $data = json_decode($message->body, true);
                if(isset($data['action'])&&$data['action'] == 'init') {
                    $this->info("Initialized listener for inventory_response_queue");
                    $resolver->acknowledge($message);
                    return;
                }

                $this->info("Received message from inventory_response_queue: " . $message->body);
                $this->listener->listen($message);
                $resolver->acknowledge($message);
            },['persistent' => true]);
        } catch (\Exception $e) {
            $this->error("Listener error: " . $e->getMessage());
        }
    }
}
