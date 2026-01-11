<?php

namespace App\Livewire;

use Livewire\Component;

class Pesanansaya extends Component
{

    public $keranjangAktif;
    public $selected = [];
    public $selectAll = false;

    public function mount()
    {
        $this->keranjangAktif = null;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->keranjangAktif->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = count($this->selected) === $this->keranjangAktif->count();
    }
    public function render()
    {
        return view('livewire.pesanansaya');
    }
}
