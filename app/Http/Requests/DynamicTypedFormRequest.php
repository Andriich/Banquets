<?php

namespace App\Http\Requests;

use App\Http\Controllers\Traits\Typable;
use Illuminate\Support\Facades\Route;

class DynamicTypedFormRequest extends DynamicFormRequest
{
    use Typable;

    public function getModelType(?string $modelType = null): ?string
    {
        if (empty($modelType)) {
            return $this->modelType ?? data_get(Route::current()->parameters(), 'type');
        }
        return $modelType;
    }

    public function __construct(?string $type = null)
    {
        parent::__construct();
        $this->setModelType($type);
    }

    public function rules(?string $action = null): array
    {
        return array_merge(
            ['type' => 'in:' . implode(',', $this->getModelTypes())],
            parent::rules($action)
        );
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'type' => $this->getModelType(),
        ]);
    }
}
