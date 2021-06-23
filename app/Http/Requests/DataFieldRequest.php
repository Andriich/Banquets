<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DataFieldRequest extends FormRequest
{
    /**
     * Name of the data field.
     *
     * @var string
     */
    protected string $dataFieldName = 'data';

    /**
     * Get data field name.
     *
     * @return string
     */
    public function dataFieldName(): string
    {
        return $this->dataFieldName;
    }

    /**
     * Set data field name.
     *
     * @return static
     */
    public function setDataFieldName(string $dataFieldName): static
    {
        $this->dataFieldName = $dataFieldName;
        return $this;
    }

    /**
     * Get data field prefix. Example: 'data.' or '' (if dataFieldName is empty)
     *
     * @return string
     */
    public function dataFieldPrefix(): string
    {
        if (empty($this->dataFieldName())) {
            return '';
        }
        return "$this->dataFieldName.";
    }

    public function getData(bool $prefixed = true, bool $validated = true): mixed
    {
        $data = $validated ? $this->validated() : $this->all();
        if ($prefixed && !empty($this->dataFieldName())) {
            return data_get($data, $this->dataFieldName(), []);
        }
        return $data;
    }

    public function __construct(string $dataFieldName = 'data')
    {
        parent::__construct();
        $this->setDataFieldName($dataFieldName);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (empty($this->dataFieldName())) {
            return [];
        }

        return [$this->dataFieldName => ['present', 'array']];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'data.present' => 'The :attribute field must be present.',
            'data.array' => 'The :attribute field must be an object or an array.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->status(400); // bad request
    }

    /**
     * Appends $dataFieldName as a prefix to all array string keys.
     *
     * @param array $rules
     * @param string|null $prefix
     * @return array
     */
    public function wrapIntoData(array $rules, ?string $prefix = null): array
    {
        $prefix = $prefix ?? $this->dataFieldPrefix();
        if (!empty($prefix)) {
            foreach ($rules as $key => $value) {
                if (is_string($key)) {
                    $rules[$prefix . $key] = $value;
                    unset($rules[$key]);
                }
            }
        }

        return array_merge(
            self::rules(),
            $rules,
        );
    }
}
