<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\AmountRule;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class TicketRequest extends DynamicFormRequest
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
            'price' => (new AmountRule(0))->make(['required']),
            'period_id' => (new IdentifierRule(0))->make(),
            'category_id' => (new IdentifierRule(0))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'name' => (new TextRule(2, 50))->make(['required']),
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'price' => (new AmountRule(0))->make(['required']),
            'period_id' => (new IdentifierRule(0))->make(),
            'category_id' => (new IdentifierRule(0))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
