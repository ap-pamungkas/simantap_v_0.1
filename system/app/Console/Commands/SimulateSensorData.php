<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\SensorDataUpdated; // Import event Anda

class SimulateSensorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensor:simulate {--interval=3 : Interval in seconds to dispatch sensor data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulates sensor data updates by dispatching SensorDataUpdated event periodically.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interval = (int) $this->option('interval');
        if ($interval < 1) {
            $this->error('Interval must be at least 1 second.');
            return Command::FAILURE;
        }

        $this->info("Simulating sensor data every {$interval} seconds. Press Ctrl+C to stop.");

        while (true) {
            // Generasi data sensor acak
            $temperature = rand(200, 350) / 10; // 20.0 - 35.0 C
            $humidity = rand(400, 800) / 10;    // 40.0 - 80.0 %

            // Memicu event
            event(new SensorDataUpdated($temperature, $humidity));

            $this->info("Dispatched: Temp={$temperature}°C, Humidity={$humidity}% at " . now()->toDateTimeString());

            sleep($interval); // Jeda sebelum mengirim data berikutnya
        }

        return Command::SUCCESS;
    }
}