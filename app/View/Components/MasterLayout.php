<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MasterLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public $title; // Tambahkan properti untuk title  

    public function __construct($title = 'App Keuangan') // Berikan nilai default  
    {
        $this->title = $title; // Simpan title ke properti  
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.master-layout', ['title' => $this->title]);
    }
}
