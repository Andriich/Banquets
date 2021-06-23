<?php

namespace App\Http\Actions;

use Illuminate\Database\Eloquent\Model;

class CreateAction extends DynamicAction
{
    protected FindAction $findAction;

    public function __construct(FindAction $findAction)
    {
        parent::__construct($findAction->model, $findAction->softDelete, $findAction->primaryKeys, $findAction->isTypable, $findAction->models);
        $this->findAction = $findAction;
    }

    public function execute(array $parameters, array $options = []): ?Model
    {
        if ($this->isTypable()) {
            $this->setModelType(data_get($options, 'type'));
        }

        return $this->create($parameters);
    }

    public function create(array $columns): ?Model
    {
        return $this->model()::create($columns);
    }

    protected function onModelTypeChanged(): void
    {
        parent::onModelTypeChanged();

        $this->findAction->setModelType($this->getModelType());
    }
}
