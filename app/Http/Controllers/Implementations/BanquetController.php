<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Actions\Implementations\CreateBanquetAction;
use App\Http\Actions\Implementations\UpdateBanquetAction;
use App\Http\Actions\SelectAction;
use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\BanquetRequest;
use App\Models\Banquet;

class BanquetController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Banquet::class;

    public function __construct(BanquetRequest $request)
    {
        parent::__construct($request);
    }

    protected function setUpActions(SelectAction $selectAction)
    {
        parent::setUpActions($selectAction);

        $this->createAction = new CreateBanquetAction($this->findAction);
        $this->updateAction = new UpdateBanquetAction($this->findAction, $this->restoreAction);
    }
}
