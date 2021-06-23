<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\AmountRule;
use App\Rules\RuleBuilders\IdentifierRule;

class DatetimeRequest extends DynamicFormRequest
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
        $prefixed = [
            'hours' => $this->dataFieldPrefix() . 'hours',
            'minutes' => $this->dataFieldPrefix() . 'minutes',
            'day' => $this->dataFieldPrefix() . 'day',
            'month' => $this->dataFieldPrefix() . 'month',
            'year' => $this->dataFieldPrefix() . 'year',
        ];

        $rules = [
            'day' => (new AmountRule(1, 31))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                'required_without_all:' . implode(',', [
                    $prefixed['month'],
                    $prefixed['year'],
                    $prefixed['hours'],
                    $prefixed['minutes'],
                ])
            ]),
            'month' => (new AmountRule(1, 12))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                'required_without_all:' . implode(',', [
                    $prefixed['day'],
                    $prefixed['year'],
                    $prefixed['hours'],
                    $prefixed['minutes'],
                ])
            ]),
            'year' => (new AmountRule(1900, 2100))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                'required_without_all:' . implode(',', [
                    $prefixed['day'],
                    $prefixed['month'],
                    $prefixed['hours'],
                    $prefixed['minutes'],
                ])
            ]),
            'hours' => (new AmountRule(0, 23))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                'required_without_all:' . implode(',', [
                    $prefixed['day'],
                    $prefixed['month'],
                    $prefixed['year'],
                    $prefixed['minutes'],
                ])
            ]),
            'minutes' => (new AmountRule(0, 59))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                'required_without_all:' . implode(',', [
                    $prefixed['day'],
                    $prefixed['month'],
                    $prefixed['year'],
                    $prefixed['hours'],
                ])
            ]),
            'is_templatable' => ['boolean', 'required'],
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make(['required']),
            'day' => (new AmountRule(1, 31))->make(['nullable']),
            'month' => (new AmountRule(1, 12))->make(['nullable']),
            'year' => (new AmountRule(1900, 2100))->make(['nullable']),
            'hours' => (new AmountRule(0, 23))->make(['nullable']),
            'minutes' => (new AmountRule(0, 59))->make(['nullable']),
            'is_templatable' => ['boolean'],
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
