<?php

namespace App\Livewire\Komando;

use App\Repositories\PerangkatRepository;
use App\Traits\Message;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PerangkatController extends Component
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
    private $perangkatRepository;

    public function boot(PerangkatRepository $perangkatRepository)
    {
        $this->perangkatRepository = $perangkatRepository;
    }

    public function render()
    {
        $data['list_devices'] = $this->perangkatRepository->getDevices(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection,
            ['Baik']

        );
        return view('livewire.komando.perangkat', $data);
    }



   
    public function updateConditions($id)
    {
        $devices = $this->perangkatRepository->updateConditions($id);
        if (!$devices) {
            $this->dispatchErrorMessage('Perangkat tidak ditemukan!');
            return;
        }
        $this->dispatchSuccesMassage("Status perangkat berhasil diubah menjadi {$devices->kondisi}", 1700);
    }
}
