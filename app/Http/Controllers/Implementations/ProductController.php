<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\ProductRequest;
use App\Models\Product;

class ProductController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Product::class;

    public function __construct(ProductRequest $request)
    {
        parent::__construct($request);
    }
}
