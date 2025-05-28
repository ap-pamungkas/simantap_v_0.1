<?php

namespace App\Livewire\Admin;

use App\Models\Jabatan;
use App\Models\Petugas;
use App\Repositories\PetugasRepository;
use App\Traits\Message;
use GuzzleHttp\Promise\Create;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class PetugasController extends Component
{

    use Message, WithFileUploads, WithPagination;
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


    public function saveData()
    {

        $data = [
            'nama' => $this->nama,
            'jabatan_id'   => $this->jabatan_id,
            'alamat' => $this->alamat,
            'tgl_lahir' => $this->tgl_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'foto' => $this->foto
        ];
        switch ($this->petugas_id) {
            case null:
                $this->validate(Petugas::$rules, Petugas::$messages);
                $this->petugasRepository->createPetugas($data);
                $this->dispatchSuccesMassage('data berhasil di simpan');
                $this->resetToCreate();
                break;

            default:
                $this->validate(Petugas::$rulesUpdate, Petugas::$messages);
                $this->petugasRepository->updatePetugas($this->petugas_id, $data);
                $this->dispatchSuccesMassage('data berhasil di perbarui');
                break;
        }
        $this->resetForm();

        $this->resetPage();
        $this->js('
            $(".modal").modal("hide")

        ');
    }

    public function resetToCreate()
    {
        $this->isEditMode = false;
        $this->reset(['nama', 'jabatan_id', 'alamat', 'tgl_lahir', 'jenis_kelamin', 'foto']);
    }

    public function deleteData($id){
        $this->petugasRepository->deletePetugas($id);
        $this->dispatchSuccesMassage('data berhasil di hapus');
        $this->js('
            $(".modal").modal("hide")

        ');
        $this->resetPage();
        $this->resetForm();

    }


    public function render()
    {
        $data['list_petugas'] = $this->petugasRepository->getPetugas(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );

        $data['list_jabatan'] = Jabatan::all();

        return view('livewire.admin.petugas.index', $data);
    }

    public function resetForm()
    {
        $this->nama = '';
        $this->jabatan_id = [];
        $this->alamat = '';
        $this->tgl_lahir = '';
        $this->jenis_kelamin = '';
        $this->foto = null;
        $this->petugas_id = null;
        // Note that we don't reset $this->jabatans
    }



    public function editData($id){

        $petugas = Petugas::find($id);
        $this->petugas_id = $petugas->id;
        $this->nama = $petugas->nama;
        $this->jabatan_id = $petugas->jabatan_id;
        $this->alamat = $petugas->alamat;
        $this->tgl_lahir = $petugas->tgl_lahir;
        $this->jenis_kelamin = $petugas->jenis_kelamin;
        $this->foto = $petugas->foto;


    }
}
