<?php

namespace App\Livewire\Komando;

use Livewire\Attributes\Layout;
use Livewire\Component;

class PetugasController extends Component
{

    #[Layout('components.layouts.komando')]
    public function render()
    {
        return view('livewire.komando.petugas');
    }
}
