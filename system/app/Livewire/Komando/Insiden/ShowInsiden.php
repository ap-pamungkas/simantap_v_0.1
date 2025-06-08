<?php

namespace App\Livewire\Komando\Insiden;

use Livewire\Component;
use App\Repositories\InsidenRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class ShowInsiden extends Component
{
    use WithPagination;

    #[Title("Insiden")]
    #[Layout("components.layouts.komando")]
    public $insiden;
    public $insidenId;
    public $nama_petugas = '';
    public $no_seri_perangkat = '';
    public $status = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $paginationTheme = 'bootstrap';

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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->loadInsiden();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['nama_petugas', 'no_seri_perangkat', 'status', 'perPage'])) {
            $this->resetPage();
            $this->loadInsiden();
        }
    }

    public function loadInsiden()
    {
        $filters = [
            'nama_petugas' => $this->nama_petugas,
            'no_seri_perangkat' => $this->no_seri_perangkat,
            'status' => $this->status,
        ];

        $this->insiden = $this->insidenRepository->getInsidenById(
            $this->insidenId,
            $filters,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );
    }

    public function render()
    {
        return view('livewire.komando.insiden.show-insiden', [
            'insiden' => $this->insiden
        ]);
    }
}