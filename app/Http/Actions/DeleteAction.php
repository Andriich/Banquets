<?php

namespace App\Http\Actions;

use App\Models\BaseDeletableModel;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends DynamicAction
{
    protected FindAction $findAction;

    public function __construct(FindAction $findAction)
    {
        parent::__construct($findAction->model, $findAction->softDelete, $findAction->primaryKeys, $findAction->isTypable, $findAction->models);
        $this->findAction = $findAction;
    }

    public function execute(array $identifiers, array $parameters = [], array $options = []): bool
    {
        if ($this->isTypable()) {
            $this->setModelType(data_get($options, 'type'));
        }

        $instance = $this->findAction->execute($identifiers, $parameters, $options);
        if (!isset($instance)) {
            return false;
        }

        return $this->delete($instance, data_get($options, 'soft', $this->isSoftDelete()));
    }

    public function delete(?Model $instance, bool $softDelete = true): bool
    {
        if (!$softDelete && $instance instanceof BaseDeletableModel) {
            return $instance->forceDelete();
        }
        return $instance->delete();
    }

    protected function onModelTypeChanged(): void
    {
        parent::onModelTypeChanged();

        $this->findAction->setModelType($this->getModelType());
    }
}
