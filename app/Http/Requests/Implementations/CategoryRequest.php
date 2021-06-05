<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicTypedFormRequest;
use App\Models\Categories\Category;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class CategoryRequest extends DynamicTypedFormRequest
{
    public function getTypes(): array
    {
        return Category::getTypes();
    }

    public function rules(?string $action = null, ?string $type = null): array
    {
        $this->type = $this->getType($type);
        return parent::rules($action);
    }

    public function storeRules(bool $wrapped = true): array
    {
        $rules = [
            'name' => (new TextRule(2, 50))->make(['required']),
            'description' => (new TextRule(2, 100))->make(['nullable']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(),
            'name' => (new TextRule(2, 50))->make(),
            'description' => (new TextRule(2, 100))->make(['nullable']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
