<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Actions\Implementations\Orders\CreateOrderAction;
use App\Http\Actions\Implementations\Orders\SelectOrderAction;
use App\Http\Actions\Implementations\Orders\UpdateOrderAction;
use App\Http\Actions\SelectAction;
use App\Http\Controllers\DynamicTypedController;
use App\Http\Requests\Implementations\OrderRequest;
use App\Models\Orders\Order;

class OrderController extends DynamicTypedController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Order::class;

    public function __construct(OrderRequest $request, ?string $type = null)
    {
        $this->models = Order::getModels();
        $this->modelTypes = Order::getModelTypes();

        parent::__construct($request, $type);
    }

    protected function createSelectAction(): SelectAction
    {
        return new SelectOrderAction();
    }

    protected function setUpActions(SelectAction $selectAction)
    {
        parent::setUpActions($selectAction);

        $this->createAction = new CreateOrderAction($this->findAction);
        $this->updateAction = new UpdateOrderAction($this->findAction, $this->restoreAction);
    }
}
