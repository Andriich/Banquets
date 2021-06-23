<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\DatetimeRequest;
use App\Models\Datetime;

class DatetimeController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Datetime::class;

    public function __construct(DatetimeRequest $request)
    {
        parent::__construct($request);
    }
}
