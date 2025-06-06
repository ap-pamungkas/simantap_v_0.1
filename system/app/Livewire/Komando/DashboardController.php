<?php

namespace App\Livewire\Komando;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DashboardController extends Component
{
    #[Title("Dashboard Komando")]
    #[Layout('components.layouts.komando')]

    public $staffOnDuty = 25;
    public $devicesUsed = 50;
    public $devicesActive = 42;
    public $mapTheme = 'light'; // Default base layer

    public $temperatureData = [
        'labels' => ['1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997'],
        'data1' => [20, 22, 24, 26, 25, 23, 27, 28, 30, 31],
        'data2' => [22, 24, 26, 28, 30, 29, 31, 32, 33, 34],
        'data3' => [18, 20, 22, 23, 24, 22, 25, 26, 27, 28],
        'data4' => [15, 16, 17, 18, 17, 16, 15, 14, 13, 12],
        'average' => [18.75, 20.5, 22.25, 23.75, 24, 22.5, 24.5, 25, 25.75, 26.25],
        'coords' => [
            ['lat' => -1.8500, 'lng' => 109.9667], // Ketapang
            ['lat' => -1.8600, 'lng' => 109.9700],
            ['lat' => -1.8400, 'lng' => 109.9600],
            ['lat' => -1.8550, 'lng' => 109.9750],
            ['lat' => -1.8450, 'lng' => 109.9650],
            ['lat' => -1.8520, 'lng' => 109.9680],
            ['lat' => -1.8580, 'lng' => 109.9620],
            ['lat' => -1.8530, 'lng' => 109.9720],
            ['lat' => -1.8470, 'lng' => 109.9670],
            ['lat' => -1.8510, 'lng' => 109.9690],
        ]
    ];

    public $co2Data = [
        'labels' => ['1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997'],
        'data1' => [350, 355, 360, 365, 362, 358, 370, 375, 380, 385],
        'data2' => [360, 365, 370, 375, 380, 378, 385, 390, 395, 400],
        'data3' => [340, 345, 350, 355, 360, 358, 362, 365, 370, 372],
        'data4' => [330, 332, 335, 338, 335, 333, 330, 328, 325, 323],
        'average' => [345, 349.25, 353.75, 358.25, 359.25, 356.75, 361.75, 364.5, 367.5, 370],
        'coords' => [
            ['lat' => -1.8500, 'lng' => 109.9667], // Ketapang
            ['lat' => -1.8600, 'lng' => 109.9700],
            ['lat' => -1.8400, 'lng' => 109.9600],
            ['lat' => -1.8550, 'lng' => 109.9750],
            ['lat' => -1.8450, 'lng' => 109.9650],
            ['lat' => -1.8520, 'lng' => 109.9680],
            ['lat' => -1.8580, 'lng' => 109.9620],
            ['lat' => -1.8530, 'lng' => 109.9720],
            ['lat' => -1.8470, 'lng' => 109.9670],
            ['lat' => -1.8510, 'lng' => 109.9690],
        ]
    ];

    public function updateMapTheme($theme)
    {
        $validThemes = ['light', 'dark', 'satellite']; // Add more valid themes here as needed
        if (in_array($theme, $validThemes)) {
            $this->mapTheme = $theme;
        }
    }

    public function render()
    {
        return view('livewire.komando.dashboard');
    }
}
