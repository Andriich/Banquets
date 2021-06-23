<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Validator;

trait Typable
{
    protected ?string $modelType = null;

    protected array $models = [];
    protected array $modelTypes = [];

    public function getModels(): array
    {
        return $this->models;
    }

    public function getModelType(): ?string
    {
        return $this->modelType;
    }

    public function getModelTypes(): array
    {
        return $this->modelTypes;
    }

    /**
     * Switches controller's model class name depending on specified type.
     *
     * @var string|null $modelType
     */
    public function setModelType(?string $modelType): void
    {
        if (!isset($modelType)) {
            return;
        }

        if (in_array($modelType, $this->getModelTypes())) {
            Validator::validate(['type' => $modelType], ['type' => 'in:' . implode(',', $this->getModelTypes())]);
        }
        $this->modelType = $modelType;
        $this->model = $this->getModels()[$this->getModelType()];

        $this->onModelTypeChanged();
    }

    protected function onModelTypeChanged(): void
    {
        //
    }
}
