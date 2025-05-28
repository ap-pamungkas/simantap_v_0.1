<?php

namespace App\Livewire\Admin\Petugas;

use App\Models\Petugas;
use App\Repositories\PetugasRepository;
use App\Traits\Message;
use Livewire\Attributes\Title;
use Livewire\Component;

class Show extends Component
{

    use Message;
    public $petugas, $status, $petugasId;


    #[Title("Detail Petugas")]

    private $petugasRepository;


    public function __construct(){
        $this->petugasRepository =  new PetugasRepository();
    }

    public function mount($id)
    {
        $this->petugas = Petugas::with('jabatan')->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.admin.petugas.show');
    }


   public function updateStatus($petugasId)
    {
        $petugas = $this->petugasRepository->updateStatus($petugasId);
        if (!$petugas) {
            $this->dispatchErrorMessage('Petugas tidak ditemukan!');
            return;
        }
        $this->status = $petugas->status;  // Update property status in component
        $this->dispatchSuccesMassage("Status berhasil diubah menjadi {$this->status}", 1700);
    }
}
