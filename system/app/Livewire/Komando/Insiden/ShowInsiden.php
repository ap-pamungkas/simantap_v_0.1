<?php

namespace App\Livewire\Komando\Insiden;

use Livewire\Component;
use App\Models\Insiden;
use App\Repositories\InsidenRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ShowInsiden extends Component
{

    #[Title("Insiden")]
    #[Layout("components.layouts.komando")]
    public $insiden;
    public $insidenId;

    private $insidenRepository;
    public function __construct()
    {
        $this->insidenRepository = new InsidenRepository();
    }
    public function mount($id)
    {
        $this->insidenId = $id;
        $this->loadInsiden();
    }

    public function loadInsiden()
    {
        $this->insiden = $this->insidenRepository->getInsidenById($this->insidenId);
    }

    

    public function render()
    {
        return view('livewire.komando.insiden.show-insiden', [
            'insiden' => $this->insiden
        ]);
    }
}
