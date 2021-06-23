<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\FindAction;

class FindOrderFieldAction extends FindAction
{
    protected function onModelTypeChanged(): void
    {
        $this->primaryKeys = ['order_id', $this->getModelType() . '_id'];
    }
}
