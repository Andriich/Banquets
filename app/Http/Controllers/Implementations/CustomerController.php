<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\CustomerRequest;
use App\Models\Customer;

class CustomerController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Customer::class;

    public function __construct(CustomerRequest $request)
    {
        parent::__construct($request);
    }
}
