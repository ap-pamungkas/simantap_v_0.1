<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LogPerangkat;
use Livewire\WithPagination;

class LogPerangkatController extends Component
{
    use WithPagination;

    public $paginationTheme = 'bootstrap';


    public function render()
    {
        return view('livewire.admin.log.log-perangkat', [
            'logs' => LogPerangkat::latest()->paginate(10),
        ]);
    }
}
