<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\BanquetStateRequest;
use App\Models\BanquetState;

class BanquetStateController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = BanquetState::class;

    public function __construct(BanquetStateRequest $request)
    {
        parent::__construct($request);
    }
}
