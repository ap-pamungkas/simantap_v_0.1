<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Title;
use Livewire\Component;

class BerandaController extends Component
{


    #[Title("Beranda")]
    public function render()
    {
        return view('livewire.admin.beranda');
    }
}
