<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Actions\Implementations\SelectSpacesAction;
use App\Http\Actions\SelectAction;
use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\SpaceRequest;
use App\Models\Space;

class SpaceController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Space::class;

    public function __construct(SpaceRequest $request)
    {
        parent::__construct($request);
    }

    protected function createSelectAction(): SelectAction
    {
        return new SelectSpacesAction($this->isSoftDelete(), $this->primaryKeys());
    }
}
