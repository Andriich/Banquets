<?php

namespace App\Http\Actions;

use App\Models\BaseDeletableModel;
use Illuminate\Database\Eloquent\Model;

class RestoreAction extends DynamicAction
{
    protected FindAction $findAction;

    public function __construct(FindAction $findAction)
    {
        parent::__construct($findAction->model, $findAction->softDelete, $findAction->primaryKeys, $findAction->isTypable, $findAction->models);
        $this->findAction = $findAction;
    }

    public function execute(array $identifiers, array $parameters = [], array $options = []): ?Model
    {
        if ($this->isTypable()) {
            $this->setModelType(data_get($options, 'type'));
        }

        $instance = $this->findAction->execute($identifiers, $parameters, $options);
        if (!isset($instance)) {
            return null;
        }

        return $this->restore($instance);
    }

    public function restore(?Model $instance): ?Model
    {
        $success = true;
        if ($this->isSoftDelete() && $instance instanceof BaseDeletableModel) {
            $success = $instance->restore();
        }

        return $success ? $instance : null;
    }

    protected function onModelTypeChanged(): void
    {
        parent::onModelTypeChanged();

        $this->findAction->setModelType($this->getModelType());
    }
}
