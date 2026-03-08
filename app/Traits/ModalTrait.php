<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait ModalTrait
{
    public bool $showingModal = false;

    #[On('openModal')]
    public function openModal()
    {
        $this->showingModal = true;
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->showingModal = false;
        $this->dispatch('resetForm');
    }

    #[On('editRecord')]
    public function editRecord($id)
    {
        $this->showingModal = true;
        $this->dispatch('edit', id: $id);
    }
}
