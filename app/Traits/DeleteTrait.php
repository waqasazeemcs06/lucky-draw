<?php

namespace App\Traits;

use App\Helpers\AlertHelper;

trait DeleteTrait
{
    public function deleteConfirm($id)
    {
        if (! isset($this->modelClass) || ! class_exists($this->modelClass)) {
            AlertHelper::error('Model class not defined in component.');
            return;
        }

        AlertHelper::confirm(
            data: ['id' => $id],
            event: 'deleteRecord',
            title: "Are you sure you want to delete this record?"
        );
    }

    public function deleteRecord($data)
    {
        $modelClass = $this->modelClass;
        $item = $modelClass::find($data['id']);

        if ($item) {
            $item->delete();
            AlertHelper::success('Record has been deleted successfully.');
        }
    }
}
