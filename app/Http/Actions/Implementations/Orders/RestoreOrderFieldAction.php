<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\RestoreAction;

class RestoreOrderFieldAction extends RestoreAction
{
    protected function onModelTypeChanged(): void
    {
        $this->primaryKeys = ['order_id', $this->getModelType() . '_id'];
    }
}
