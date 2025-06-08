<?php

namespace App\Livewire\Komando;

use App\Repositories\PetugasRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PetugasController extends Component
{
    use  WithPagination;
    #[Title("Petugas")]

     
    public $petugas_id,
        $nama,
        $alamat,
        $jabatan_id,
        $tgl_lahir,
        $jenis_kelamin,
        $foto;

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

 private $petugasRepository;


   public function __construct(){
       $this->petugasRepository = new PetugasRepository();
   }

    public function close()
    {
        $this->reset();
    }

    #[Layout('components.layouts.komando')]
    public function render()
    {
        $data['list_petugas'] = $this->petugasRepository->getPetugas(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );

        return view('livewire.komando.petugas', $data);
    }
}
