<?php

namespace App\Rules\RuleBuilders;

class AmountRule extends RuleBuilder
{
    protected ?int $min, $max;

    /**
     * TextRule constructor.
     * @param ?int $min
     * @param ?int $max
     */
    public function __construct(?int $min = null, ?int $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function make(array $additionalRules = []): array
    {
        $rules = ['numeric'];
        if (isset($this->min)) {
            $rules[] = "min:$this->min";
        }
        if (isset($this->max)) {
            $rules[] = "max:$this->max";
        }
        return $this->concat($rules, $additionalRules);
    }
}
