<?php

namespace App\Livewire\Admin;

use App\Repositories\PerangkatRepository;
use App\Traits\Message;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PerangkatController extends Component
{

    use  WithPagination, Message;
    public $selectedId;
    public $no_seri;
    public $isEditMode = false;
    public $showModal = false;

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
    

    #[Title("Perangkat")]
    public function render()
    {

        $data['list_devices'] = $this->perangkatRepository->getDevices(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection,
            ['Baik', 'Rusak']
        );


        // dd($data);


        return view('livewire.admin.perangkat.index', $data);
    }



public function confirmDelete($id){
   $this->selectedId = $id;
}
    public function deleteData($id)
    {
        // dd($id);
        $this->perangkatRepository->deleteDevices($id);
       
        $this->dispatchSuccesMassage('data berhasil di hapus');
        $this->js('
            $(".modal").modal("hide")

        ');
        $this->resetPage();
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





    public function close()
    {

        $this->resetPage();
        $this->reset(['search', 'perPage', 'sortField', 'sortDirection']);
    }
}
