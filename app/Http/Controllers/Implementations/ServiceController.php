<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\ServiceRequest;
use App\Models\Service;

class ServiceController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Service::class;

    public function __construct(ServiceRequest $request)
    {
        parent::__construct($request);
    }
}
