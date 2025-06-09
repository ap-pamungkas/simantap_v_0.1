<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $temperature;
    public $humidity;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(float $temperature, float $humidity)
    {
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->timestamp = now()->toDateTimeString(); // Tambahkan waktu saat ini
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Channel publik bernama 'sensor-data'
        return [
            new Channel('sensor-data'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'sensor.updated'; // Nama event di frontend
    }
}