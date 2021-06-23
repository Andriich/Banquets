<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\ImperiaRoleRequest;
use App\Models\ImperiaRole;

class ImperiaRoleController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = ImperiaRole::class;

    public function __construct(ImperiaRoleRequest $request)
    {
        parent::__construct($request);
    }
}
