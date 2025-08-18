<?php

namespace App\Console\Commands;

use App\Inventories\Infrastructure\Messaging\PurchaseResponseListener;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

/**
 * Listener class to listen for purchase responses
 * Class PurchaseInventoryListener
 * @package App\Console\Commands
 */
class PurchaseInventoryListener extends Command
{
    protected $signature = 'inventory-purchased:listen';

    protected $description = 'Start the inventory listener to listen for purchase responses';

    protected $purchaseResponseListener;

    public function __construct(PurchaseResponseListener $purchaseResponseListener)
    {
        parent::__construct();
        $this->purchaseResponseListener = $purchaseResponseListener;
    }
    /**
     * Listen messages from the message broker queue
     */
    public function handle()
    {
        try {
            // Initialize the queue
            Amqp::publish('routing-key-4', json_encode([
                'action' => 'init'  
            ]), [
                'queue' => 'purchase_response_queue',
                'exchange' => 'exchange_purchase_response',  
                'exchange_type' => 'direct'         
            ]);
            $this->info("Start listening to purchase_response_queue");
            Amqp::consume('purchase_response_queue', function ($message, $resolver) {
                $data = json_decode($message->body, true);
                if(isset($data['action'])&&$data['action'] == 'init') {
                    $this->info("Initialized listener for purchase_response_queue");
                    $resolver->acknowledge($message);
                    return;
                }
                $this->info("Received message from purchase_response_queue: " . $message->body);
                $this->purchaseResponseListener->listen($message);
                $resolver->acknowledge($message);
            },['persistent' => true]);
        } catch (\Exception $e) {
            $this->error("Listener error: " . $e->getMessage());
        }
    }
}
