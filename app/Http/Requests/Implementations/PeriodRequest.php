<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class PeriodRequest extends DynamicFormRequest
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
            'beg_datetime_id' => (new IdentifierRule(0))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                "required_without:{$this->dataFieldPrefix()}end_datetime_id"
            ]),
            'end_datetime_id' => (new IdentifierRule(0))->make([
                'nullable',
                "required_if:{$this->dataFieldPrefix()}is_templatable,false",
                "required_without:{$this->dataFieldPrefix()}beg_datetime_id"]),
            'weekdays' => (new TextRule(0, 13, TextRule::PERIOD_WEEKDAYS_REGEX))->make(),
            'is_templatable' => ['required', 'boolean'],
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'beg_datetime_id' => (new IdentifierRule(0))->make(['nullable']),
            'end_datetime_id' => (new IdentifierRule(0))->make(['nullable']),
            'weekdays' => (new TextRule(0, 13, TextRule::PERIOD_WEEKDAYS_REGEX))->make(),
            'is_templatable' => ['required', 'boolean'],
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
