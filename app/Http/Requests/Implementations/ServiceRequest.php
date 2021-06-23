<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\AmountRule;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class ServiceRequest extends DynamicFormRequest
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
            'once_paid_price' => (new AmountRule(0))->make(['nullable', 'required_without:hourly_paid_price']),
            'hourly_paid_price' => (new AmountRule(0))->make(['nullable', 'required_without:once_paid_price']),
            'period_id' => (new IdentifierRule(0))->make(['nullable']),
            'category_id' => (new IdentifierRule(0))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'name' => (new TextRule(2, 50))->make(),
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'once_paid_price' => (new AmountRule(0))->make(['nullable']),
            'hourly_paid_price' => (new AmountRule(0))->make(['nullable']),
            'period_id' => (new IdentifierRule(0))->make(['nullable']),
            'category_id' => (new IdentifierRule(0))->make(),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
