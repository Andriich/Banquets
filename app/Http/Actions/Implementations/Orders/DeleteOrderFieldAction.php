<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\DeleteAction;

class DeleteOrderFieldAction extends DeleteAction
{
    protected function onModelTypeChanged(): void
    {
        $this->primaryKeys = ['order_id', $this->getModelType() . '_id'];
    }
}
