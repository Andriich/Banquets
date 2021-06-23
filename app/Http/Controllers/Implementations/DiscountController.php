<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\DiscountRequest;
use App\Models\Discount;

class DiscountController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Discount::class;

    public function __construct(DiscountRequest $request)
    {
        parent::__construct($request);
    }
}
