<?php

namespace App\Helpers;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AlertHelper extends LivewireAlert
{

    public static function success($content = '', $title = 'Success')
    {
        LivewireAlert::title($title)
            ->withOptions([
                'html' => $content
            ])
            ->success()
            ->show();
    }

    public static function error($content = '', $title = 'Error')
    {
        LivewireAlert::title($title)
            ->withOptions([
                'html' => $content
            ])
            ->error()
            ->show();
    }

    public static function warning($content = '', $title = 'Warning')
    {
        LivewireAlert::title($title)
            ->withOptions([
                'html' => $content
            ])
            ->warning()
            ->show();
    }

    public static function info($content = '', $title = 'Info')
    {
        LivewireAlert::title($title)
            ->withOptions([
                'html' => $content
            ])
            ->info()
            ->show();
    }

    public static function confirm($content = '', $data = [], $event = '', $title = 'Confirm', $options = [])
    {
        LivewireAlert::title($title)
            ->withOptions(array_merge([
                'timer' => null,
                'html' => $content,
                'allowOutsideClick' => false,
                'allowEscapeKey' => false,
                'showCancelButton' => false,
            ], $options))
            ->asConfirm()
            ->onConfirm($event, $data)
            ->show();
    }

    public static function confirmPrompt($content = '', $event = '', $title = 'Confirm', $expected = 'DELETE', $options = [])
    {
        LivewireAlert::title($title)
            ->withOptions(array_merge([
                'html' => $content,
                'input' => 'text',
                'inputPlaceholder' => "Type {$expected} to confirm",
                'inputValidator' => "value => { if (!value) { return 'Type {$expected} to confirm'; } if (value.trim() !== '{$expected}') { return 'Type {$expected} to confirm'; } }",
                'allowOutsideClick' => false,
                'allowEscapeKey' => false,
            ], $options))
            ->asConfirm()
            ->onConfirm($event)
            ->show();
    }

}
