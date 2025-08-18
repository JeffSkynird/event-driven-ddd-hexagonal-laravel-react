<?php

namespace App\Purchases\Domain\CircuitBreakers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Circuit breaker to prevent the purchase process from executing when the circuit is open
 * Class MarketCircuitBreaker
 * @package App\Purchases\Domain\CircuitBreakers
 */
class MarketCircuitBreaker
{
    private $failureCount = 0;
    private $failureThreshold = 3; // number of failures before tripping the circuit
    private $timeout = 30; // time in seconds to keep the circuit open

    /**
     * Execute the purchase process
     * @param callable $callback
     * @return mixed
     */
    public function execute(callable $callback)
    {
        if ($this->isOpen()) {
            Log::error("Open circuit. Cannot perform purchase at this time.");
            throw new \Exception("Open circuit. Cannot perform purchase at this time.");
        }
        try {
            $result = $callback();
            $this->reset(); // Reiniciar el estado si el llamado es exitoso
            return $result;
        } catch (\Exception $e) {
            $this->recordFailure();
            if ($this->failureCount >= $this->failureThreshold) {
                $this->trip(); // Abrir el circuito
            }
            throw $e;
        }
    }

    private function isOpen()
    {
        return Cache::get('market_circuit_breaker') === 'open';
    }

    private function recordFailure()
    {
        $this->failureCount++;
    }

    private function reset()
    {
        $this->failureCount = 0;
    }
    /**
     * Save the circuit state as open
     */
    private function trip()
    {
        Cache::put('market_circuit_breaker', 'open', $this->timeout);
    }
}
