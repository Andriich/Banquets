<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\SelectAction;
use App\Models\Orders\Order;

class SelectOrderAction extends SelectAction
{
    public function __construct(bool $softDelete = true)
    {
        parent::__construct(Order::class, $softDelete, ['id'], true, Order::getModels());
    }
}
