<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Models\Banquet;
use App\Rules\RuleBuilders\AmountRule;
use App\Rules\RuleBuilders\DateTimeRule;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class BanquetRequest extends DynamicFormRequest
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
            'advance_amount' => (new AmountRule(0))->make(['required']),
            'beg_datetime' => (new DateTimeRule())->make(['required']),
            'end_datetime' => (new DateTimeRule())->make(['required']),
            'state_id' => (new IdentifierRule(0))->make(['required']),
            'creator_id' => (new IdentifierRule(0))->make(['required']),
            'customer_id' => (new IdentifierRule(0))->make(['required']),
            'comments' => ['nullable', 'array'],
            'comments.*.text' => (new TextRule(1, 100))->make(['required']),
            'comments.*.target_id' => (new IdentifierRule(0))->make(['required']),
            'comments.*.target_type' => (new TextRule(2, 100))->make(['required']),
        ];

        foreach (Banquet::getOrderColumnNames() as $orderType => $orderName) {
            $rules[$orderName] = [];
        }
        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(),
            'name' => (new TextRule(2, 50))->make(),
            'description' => (new TextRule(2, 100))->make(['nullable']),
            'advance_amount' => (new AmountRule(0))->make(),
            'beg_datetime' => (new DateTimeRule())->make(),
            'end_datetime' => (new DateTimeRule())->make(),
            'state_id' => (new IdentifierRule(0))->make(),
            'creator_id' => (new IdentifierRule(0))->make(),
            'customer_id' => (new IdentifierRule(0))->make(),
            'comments' => ['nullable', 'array'],
            'comments.*.id' => (new IdentifierRule(0))->make([
                'required_without:' . $this->dataFieldPrefix() . 'comments.*.text'
            ]),
            'comments.*.text' => (new TextRule(1, 100))->make([
                'required_without:' . $this->dataFieldPrefix() . 'comments.*.id'
            ]),
            'comments.*.target_id' => (new IdentifierRule(0))->make(['required']),
            'comments.*.target_type' => (new TextRule(2, 100))->make(['required']),
        ];

        foreach (Banquet::getOrderColumnNames() as $orderType => $orderName) {
            $rules[$orderName] = [];
        }
        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
