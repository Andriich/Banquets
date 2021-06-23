<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\CreateAction;

class CreateOrderFieldAction extends CreateAction
{
    protected function onModelTypeChanged(): void
    {
        $this->primaryKeys = ['order_id', $this->getModelType() . '_id'];
    }
}
