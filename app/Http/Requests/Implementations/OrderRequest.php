<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicTypedFormRequest;
use App\Models\Orders\Order;
use App\Rules\RuleBuilders\AmountRule;
use App\Rules\RuleBuilders\DateTimeRule;
use App\Rules\RuleBuilders\IdentifierRule;

class OrderRequest extends DynamicTypedFormRequest
{
    public function __construct(?string $type = null)
    {
        $this->models = Order::getModels();
        $this->modelTypes = Order::getModelTypes();

        parent::__construct($type);
    }

    public function rules(?string $action = null): array
    {
        $this->type = $this->getModelType($this->type);
        return parent::rules($action);
    }

    public function storeRules(bool $wrapped = true): array
    {
        $rules = [
            'banquet_id' => (new IdentifierRule(0))->make(['required']),
            'discount_id' => (new IdentifierRule(0))->make(['nullable']),
        ];

        $rules = array_merge($rules, $this->itemRules());
        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make([
                'required_without:' . $this->dataFieldPrefix() . 'banquet_id'
            ]),
            'banquet_id' => (new IdentifierRule(0))->make([
                'required_without:' . $this->dataFieldPrefix() . 'id'
            ]),
            'discount_id' => (new IdentifierRule(0))->make(['nullable']),
        ];

        $rules = array_merge($rules, $this->itemRules());
        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function itemRules(?string $type = null): array
    {
        $rules = [
            'items' => ['nullable', 'array'],
            'items.*.id' => (new IdentifierRule(0))->make(['required']),
        ];

        switch ($this->getModelType()) {
            case 'space':
                $rules['items.*.beg_datetime'] = (new DateTimeRule())->make(['required']);
                $rules['items.*.end_datetime'] = (new DateTimeRule())->make(['required']);
                break;
            case 'ticket':
                $rules['items.*.amount'] = (new AmountRule(0))->make(['required']);
                break;
            case 'service':
                $rules['items.*.amount'] = (new AmountRule(0))->make(['required']);
                $rules['items.*.duration'] = (new AmountRule(0))->make(['required']);
                break;
            case 'product':
                $rules['items.*.amount'] = (new AmountRule(0))->make(['required']);
                break;
        }
        return $rules;
    }
}
