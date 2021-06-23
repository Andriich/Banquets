<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\DateRule;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class CustomerRequest extends DynamicFormRequest
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
            'name' => (new TextRule(2, 50))->make(['required']),
            'surname' => (new TextRule(2, 50))->make(['required']),
            'phone' => (new TextRule(2, 100, TextRule::PHONE_REGEX))->make(['required', 'unique:customers']),
            'email' => (new TextRule(2, 100))->make(['email']),
            'birthdate' => (new DateRule())->make(),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(),
            'name' => (new TextRule(2, 50))->make(),
            'surname' => (new TextRule(2, 50))->make(),
            'phone' => (new TextRule(2, 100, TextRule::PHONE_REGEX))->make(),
            'email' => (new TextRule(2, 100))->make(['email']),
            'birthdate' => (new DateRule())->make(),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
