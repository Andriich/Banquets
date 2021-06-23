<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\actions;
use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class ImperiaUserRequest extends DynamicFormRequest
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

    public function actions(): array
    {
        return array_merge(
            parent::actions(),
            ['login']
        );
    }

    public function storeRules(bool $wrapped = true): array
    {
        $rules = [
            'name' => (new TextRule(2, 50))->make(['required', 'unique:imperia_users']),
            'password' => (new TextRule(8, 50))->make(['required']),
            'role_id' => (new IdentifierRule(0))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(),
            'name' => (new TextRule(2, 50))->make(),
            'password' => (new TextRule(8, 50))->make(),
            'role_id' => (new IdentifierRule(0))->make(),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function loginRules(bool $wrapped = false): array
    {
        $rules = [
            'name' => (new TextRule(2, 50))->make(['required_without:api_token']),
            'password' => (new TextRule(8, 50))->make(['required_without:api_token']),
            'api_token' => (new TextRule(0, null, null))->make(['required_without_all:name,password']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
