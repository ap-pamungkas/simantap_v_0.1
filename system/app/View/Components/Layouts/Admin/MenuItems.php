<?php

namespace App\View\Components\Layouts\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItems extends Component
{
    /**
     * Create a new component instance.
     */

     public $label;
     public $icon;
     public $active;
     public $url;
    public function __construct($icon = null)
    {
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.admin.menu-items');
    }
}
