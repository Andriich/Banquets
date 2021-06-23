<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\CustomerFamilyMemberRequest;
use App\Models\CustomerFamilyMember;

class CustomerFamilyMemberController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = CustomerFamilyMember::class;

    public function __construct(CustomerFamilyMemberRequest $request)
    {
        parent::__construct($request);
    }
}
