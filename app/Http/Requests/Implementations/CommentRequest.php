<?php

namespace App\Http\Requests\Implementations;

use App\Http\Requests\DynamicFormRequest;
use App\Rules\RuleBuilders\IdentifierRule;
use App\Rules\RuleBuilders\TextRule;

class CommentRequest extends DynamicFormRequest
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
            'text' => (new TextRule(2, 50))->make(['required']),
            'target_id' => (new IdentifierRule(0))->make(['required']),
            'target_type' => (new TextRule(2, 35))->make(['required']),
            'container_id' => (new IdentifierRule(0))->make(['required']),
            'container_type' => (new TextRule(2, 35))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }

    public function updateRules(bool $wrapped = true): array
    {
        $rules = [
            'id' => (new IdentifierRule(0))->make([
                'required_without:' . $this->dataFieldPrefix() . 'text'
            ]),
            'text' => (new TextRule(2, 50))->make([
                'required_without:' . $this->dataFieldPrefix() . 'id'
            ]),
            'target_id' => (new IdentifierRule(0))->make(['required']),
            'target_type' => (new TextRule(2, 35))->make(['required']),
            'container_id' => (new IdentifierRule(0))->make(['required']),
            'container_type' => (new TextRule(2, 35))->make(['required']),
        ];

        return $wrapped ? $this->wrapIntoData($rules) : $rules;
    }
}
