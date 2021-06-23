<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\CreateAction;
use App\Http\Actions\FindAction;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateOrderAction extends CreateAction
{
    public function __construct(FindAction $findAction)
    {
        parent::__construct($findAction);
    }

    public function create(array $columns): ?Model
    {
        $items = Arr::pull($columns, 'items', []);

        $instance = parent::create($columns);
        if (!isset($instance)) {
            return null;
        }

        $fields = Order::toFields($instance, $items);
        foreach ($fields as $field) {
            if (!$field->save()) {
                return null;
            }
        }

        return $this->findAction->find($this->extractIdentifiers($instance));
    }
}
