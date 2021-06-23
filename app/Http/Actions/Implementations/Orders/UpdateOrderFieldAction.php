<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\UpdateAction;

class UpdateOrderFieldAction extends UpdateAction
{
    public function __construct()
    {
        $selectAction = new SelectOrderFieldAction();
        $findAction = new FindOrderFieldAction($selectAction);
        $restoreAction = new RestoreOrderFieldAction($findAction);

        parent::__construct($findAction, $restoreAction);
    }

    protected function onModelTypeChanged(): void
    {
        $this->primaryKeys = ['order_id', $this->getModelType() . '_id'];
    }
}
