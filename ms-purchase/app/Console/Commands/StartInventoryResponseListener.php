<?php

namespace App\Console\Commands;

use App\Purchases\Infrastructure\Messaging\PurchaseRequestListener;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;

/**
 * Listener class to listen for purchase requests
 * Class StartInventoryResponseListener
 * @package App\Console\Commands
 */
class StartInventoryResponseListener extends Command
{
    protected $signature = 'purchase-request:listen';

    protected $description = 'Start the purchase listener to listen for purchase requests';

    protected $listener;

    public function __construct(PurchaseRequestListener $listener)
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
            Amqp::publish('routing-key-3', json_encode([
                'action' => 'init'
            ]), [
                'queue' => 'purchase_request_queue',
                'exchange' => 'exchange_purchase_request',
                'exchange_type' => 'direct'
            ]);
            $this->info("Start listening to purchase_request_queue");
            Amqp::consume('purchase_request_queue', function ($message, $resolver) {
                $data = json_decode($message->body, true);
                if (isset($data['action'])&&$data['action'] == 'init') {
                    $this->info("Initialized listener for purchase_request_queue");
                    $resolver->acknowledge($message);
                    return;
                }
                $this->info("Received message from purchase_request_queue: " . $message->body);
                $this->listener->listen($message);
                $resolver->acknowledge($message);
            },['persistent' => true]);
        } catch (\Exception $e) {
            $this->error("Listener error: " . $e->getMessage());
        }
    }
}
