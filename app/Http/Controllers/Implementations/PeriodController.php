<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\PeriodRequest;
use App\Models\Period;

class PeriodController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Period::class;

    public function __construct(PeriodRequest $request)
    {
        parent::__construct($request);
    }
}
