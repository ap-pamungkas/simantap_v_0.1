<?php

namespace App\Livewire\Admin;

use App\Models\Jabatan;
use App\Repositories\JabatanRepository;
use App\Traits\Message;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class JabatanController extends Component
{
    use Message, WithPagination;
    #[Title("Jabatan")]

    public $jabatan_id = null, $nama_jabatan, $isEditMode = false, $selectedId;
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
    private $jabatanRepository;


    public function __construct()
    {
        $this->jabatanRepository = new JabatanRepository();
    }

    public function render()
    {
        $data['list_jabatan'] = $this->jabatanRepository->getJabatan(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );
        return view('livewire.admin.jabatan.index', $data);
    }

    // close modal
    public function close()
    {
        $this->reset();
    }
    // insert data jabatan
    public function saveData()
    {

        $data = [
            'nama_jabatan' => $this->nama_jabatan,
        ];
        switch ($this->jabatan_id) {
            case null:
                $this->validate(Jabatan::$rules, Jabatan::$messages);
                $this->jabatanRepository->createJabatan($data);
                $this->dispatchSuccesMassage('data berhasil di simpan');
                $this->resetToCreate();
                break;

            default:
                $this->jabatanRepository->updatejabatan($this->jabatan_id, $data);
                $this->dispatchSuccesMassage('data berhasil di perbarui');
                break;
        }
        $this->reset();
        $this->resetPage();
        $this->js('
            $(".modal").modal("hide")

        ');
        // $this->dispatchBrowserEvent('close-modal');

    }

    public function resetToCreate()
    {
        $this->isEditMode = false;
        $this->reset(['selectedId', 'nama_jabatan']);
    }




    public function editData($id)
    {

        $jabatan = Jabatan::find($id);
        $this->jabatan_id = $jabatan->id;
        $this->nama_jabatan = $jabatan->nama_jabatan;

        // dd($jabatan);

    }

    public function confirmDelete($id){
        $this->selectedId = $id;
     }

    public function deleteData($id){
        $this->jabatanRepository->deleteJabatan($id);
        $this->reset();
        $this->resetPage();
        $this->js('
            $(".modal").modal("hide")');
        $this->dispatchSuccesMassage('data berhasil di hapus');
    }



}


