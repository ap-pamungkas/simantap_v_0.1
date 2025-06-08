<?php

namespace App\Livewire\Komando;

use App\Repositories\InsidenRepository;
use App\Traits\Message;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class InsidenController extends Component
{
    use  WithPagination, Message;
    #[Title("Perangkat")]
    #[Layout("components.layouts.komando")]
    public $selectedId;
    public $no_seri;
    public $isEditMode = false;

    public $search = '';

    public $perPage = 10;
    // public $sortField, $sortDirection ;

    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $paginationTheme = 'bootstrap';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    private $insidenRepository;
    public function __construct()
    {
        $this->insidenRepository = new InsidenRepository();
    }
    public function render()
    {
        $data['list_insidens']=$this->insidenRepository->getInsiden(   $this->search,
        $this->perPage,
        $this->sortField,
        $this->sortDirection);
        return view('livewire.komando.insiden', $data);
    }
}
