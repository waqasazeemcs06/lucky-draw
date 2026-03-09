<?php

use App\Traits\ModalTrait;
use Livewire\Component;

new class extends Component {
    use ModalTrait;
    public $draw_id = null;
};
?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @livewire('admin.winner.table', ['draw_id' => $draw_id])
            </div>
        </div>
    </div>
</div>
