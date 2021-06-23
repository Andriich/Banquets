<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\ImperiaMenuRequest;
use App\Models\ImperiaMenu;

class ImperiaMenuController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = ImperiaMenu::class;

    public function __construct(ImperiaMenuRequest $request)
    {
        parent::__construct($request);
    }
}
