<?php

namespace App\Rules\RuleBuilders;

abstract class RuleBuilder
{
    /**
     * Get an array of validation rules.
     *
     * @param array $additionalRules
     * @return array
     */
    public abstract function make(array $additionalRules = []): array;

    public function concat(array $rules, array $additionalRules = []): array
    {
        return array_merge($additionalRules, $rules);
    }
}
