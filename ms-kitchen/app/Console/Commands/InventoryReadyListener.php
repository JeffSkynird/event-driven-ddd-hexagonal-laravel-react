<?php

namespace App\Console\Commands;

use App\Kitchen\Infrastructure\Messaging\InventoryReadyPreapareListener;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

/**
 * Listener class to listen for inventory-purchases responses
 * Class InventoryReadyListener
 * @package App\Console\Commands
 */
class InventoryReadyListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory-ready:listen';

    protected $description = 'Start the inventory listener to listen for inventory ready responses';

    protected $listener;

    public function __construct(InventoryReadyPreapareListener $listener)
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
            Amqp::publish('routing-key-5', json_encode([
                'action' => 'init'  
            ]), [
                'queue' => 'kitchen_order_queue', 
                'exchange' => 'exchange_kitchen', 
                'exchange_type' => 'direct'
            ]);
            $this->info("Start listening to kitchen_order_queue");
            Amqp::consume('kitchen_order_queue', function ($message, $resolver) {
                $data = json_decode($message->body, true);
                if(isset($data['action'])&&$data['action'] == 'init') {
                    $this->info("Initialized listener for kitchen_order_queue");
                    $resolver->acknowledge($message);
                    return;
                }
                $this->info("Received message from kitchen_order_queue: " . $message->body);
                $this->listener->listen($message); 
                $resolver->acknowledge($message);
            },['persistent' => true]);
        } catch (\Exception $e) {
            $this->error("Listener error: " . $e->getMessage());
        }
    }
}
