<?php

namespace App\Http\Actions;

use Illuminate\Database\Eloquent\Model;

class UpdateAction extends DynamicAction
{
    protected FindAction $findAction;
    protected RestoreAction $restoreAction;

    public function __construct(FindAction $findAction, RestoreAction $restoreAction)
    {
        parent::__construct($findAction->model, $findAction->softDelete, $findAction->primaryKeys, $findAction->isTypable, $findAction->models);
        $this->findAction = $findAction;
        $this->restoreAction = $restoreAction;
    }

    public function execute(array $identifiers, array $parameters = [], array $options = []): ?Model
    {
        if ($this->isTypable()) {
            $this->setModelType(data_get($options, 'type'));
        }

        $instance = $this->findAction->execute($identifiers, [], $options);
        if (data_get($options, 'restore', false)) {
            $instance = $this->restoreAction->restore($instance);
        }
        return $this->update($instance, $parameters);
    }

    public function update(?Model $instance, array $columns): ?Model
    {
        if (!isset($instance) || !$instance->update($columns)) {
            return null;
        }
        return $instance;
    }

    protected function onModelTypeChanged(): void
    {
        parent::onModelTypeChanged();

        $this->findAction->setModelType($this->getModelType());
        $this->restoreAction->setModelType($this->getModelType());
    }
}
