<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicTypedController;
use App\Http\Requests\Implementations\CategoryRequest;
use App\Models\Categories\Category;

class CategoryController extends DynamicTypedController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Category::class;

    public function __construct(CategoryRequest $request, ?string $type = null)
    {
        $this->models = Category::getModels();
        $this->modelTypes = Category::getModelTypes();

        parent::__construct($request, $type);
    }
}
