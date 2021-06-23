<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class ImperiaRoleRequest extends DynamicFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function storeRules(bool $wrapped = true): array
    {
        $rules = [
            'name' => (new TextRule(2, 50))->make(['required', 'unique:imperia_roles']),
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'can_read' => ['required', 'boolean'],
            'can_insert' => ['required', 'boolean'],
            'can_modify' => ['required', 'boolean'],
            'is_owner' => ['required', 'boolean'],
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(),
            'name' => (new TextRule(2, 50))->make(),
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'can_read' => ['boolean'],
            'can_insert' => ['boolean'],
            'can_modify' => ['boolean'],
            'is_owner' => ['boolean'],
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
