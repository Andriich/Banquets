<?php

namespace App\Http\Actions;

use App\Http\Controllers\Traits\Filterable;
use App\Http\Controllers\Traits\Identifiable;
use App\Http\Controllers\Traits\Sortable;
use App\Http\Controllers\Traits\Typable;

abstract class DynamicAction
{
    use Identifiable, Filterable, Sortable, Typable;

    protected bool $isTypable = false;

    public function isTypable(): bool
    {
        return $this->isTypable;
    }

    public function __construct(string $model, bool $softDelete = true, array $primaryKeys = ['id'], bool $isTypable = false, array $models = [])
    {
        $this->model = $model;
        $this->softDelete = $softDelete;
        $this->primaryKeys = $primaryKeys;
        $this->isTypable = $isTypable;
        $this->models = $models;
        $this->modelTypes = array_keys($models);
    }

    public abstract function execute(array $parameters, array $options = []): mixed;

    protected function onModelTypeChanged(): void
    {

    }
}
