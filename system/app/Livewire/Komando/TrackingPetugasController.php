<?php

namespace App\Livewire\Komando;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class TrackingPetugasController extends Component
{

    #[Layout('components.layouts.komando')]
    #[Title("Pemantauan Petugas")]
    
    public function render()
    {
        return view('livewire.komando.tracking-petugas');
    }
}
