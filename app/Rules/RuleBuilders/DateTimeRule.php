<?php

namespace App\Rules\RuleBuilders;

class DateTimeRule extends RuleBuilder
{
    protected ?string $regex;

    /**
     * TextRule constructor.
     * @param ?string $regex
     */
    public function __construct(?string $regex = null)
    {
        $this->regex = $regex;
    }

    public function make(array $additionalRules = []): array
    {
        $rules = ['date_format:Y-m-d H:i:s'];
        if (isset($this->regex)) {
            $rules[] = "regex:/$this->regex/";
        }
        return $this->concat($rules, $additionalRules);
    }
}
