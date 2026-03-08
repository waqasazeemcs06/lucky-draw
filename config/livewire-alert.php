<?php

/*
 * For more details about the configuration, see:
 * https://sweetalert2.github.io/#configuration
 */

use Jantinnerezo\LivewireAlert\Enums\Position;

return [
    'position' => Position::Center,
    'timer' => 5000,
    'toast' => false,
    'text' => null,
    'confirmButtonText' => 'Yes',
    'cancelButtonText' => 'Cancel',
    'denyButtonText' => 'No',
    'showCancelButton' => false,
    'showConfirmButton' => false,
    'backdrop' => true,
    'timerProgressBar' => true,
    'allowOutsideClick' => false,
    'showCloseButton' => true,
    'allowEscapeKey' => true,
    'didOpen' => <<<'JS'
                    (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                JS
];
