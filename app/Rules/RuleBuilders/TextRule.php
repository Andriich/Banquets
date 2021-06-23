<?php

namespace App\Rules\RuleBuilders;

class TextRule extends RuleBuilder
{
    public const TEXT_REGEX = '(^\S$|^\S(.*)\S$)';
    public const PASSWORD_REGEX = null;
    public const PHONE_REGEX = '(^[+][0-9]+([ -][0-9]+)*$)';
    public const PERIOD_WEEKDAYS_REGEX = '^$|^([1-7][,]){0,6}([1-7])$';

    protected ?int $min, $max;
    protected ?string $regex;

    /**
     * TextRule constructor.
     * @param ?int $min
     * @param ?int $max
     * @param ?string $regex
     */
    public function __construct(?int $min = null, ?int $max = null, ?string $regex = self::TEXT_REGEX)
    {
        $this->min = $min;
        $this->max = $max;
        $this->regex = $regex;
    }

    public function make(array $additionalRules = []): array
    {
        $rules = [];
        if (isset($this->min)) {
            $rules[] = "min:$this->min";
        }
        if (isset($this->max)) {
            $rules[] = "max:$this->max";
        }
        if (isset($this->regex)) {
            $rules[] = "regex:$this->regex";
        }
        return $this->concat($rules, $additionalRules);
    }
}
