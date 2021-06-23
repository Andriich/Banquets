<?php

namespace App\Custom;

class AttributeExtractionException extends \Exception
{
    protected ?string $key;
    protected ?string $reason;

    /**
     * AttributeAbsenceException constructor.
     * @param string|null $key
     * @param string|null $reason
     */
    public function __construct(?string $key = null, ?string $reason = null)
    {
        $this->key = $key;
        $this->reason = $reason;

        if (!empty($key)) {
            $this->message = "$key attribute is missing.";
        } else {
            $this->message = $reason;
        }
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
