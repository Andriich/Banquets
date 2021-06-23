<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class DiscountRequest extends DynamicFormRequest
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
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'amount' => (new TextRule(0))->make(['required_without:percent']),
            'percent' => (new TextRule(0, 100))->make(['required_without:amount']),
            'period_id' => (new IdentifierRule(0))->make(['nullable']),
            'category_id' => (new IdentifierRule(0))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(),
            'name' => (new TextRule(2, 50))->make(),
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'amount' => (new TextRule(0))->make(),
            'percent' => (new TextRule(0, 100))->make(),
            'period_id' => (new IdentifierRule(0))->make(['nullable']),
            'category_id' => (new IdentifierRule(0))->make(),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
