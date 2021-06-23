<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\SelectAction;
use App\Models\Orders\Order;

class SelectOrderFieldAction extends SelectAction
{
    public function __construct(bool $softDelete = true)
    {
        parent::__construct(Order::class, $softDelete, ['order_id', 'item_id'], true, Order::getModelFields());
    }

    protected function onModelTypeChanged(): void
    {
        parent::onModelTypeChanged();

        $this->primaryKeys = ['order_id', $this->getModelType() . '_id'];
    }
}
