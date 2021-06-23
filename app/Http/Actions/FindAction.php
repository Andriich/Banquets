<?php

namespace App\Http\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class FindAction extends DynamicAction
{
    protected SelectAction $selectAction;

    public function __construct(SelectAction $selectAction)
    {
        parent::__construct($selectAction->model, $selectAction->softDelete, $selectAction->primaryKeys, $selectAction->isTypable, $selectAction->models);
        $this->selectAction = $selectAction;
    }

    public function execute(array $identifiers, array $parameters = [], array $options = []): ?Model
    {
        if ($this->isTypable()) {
            $this->setModelType(Arr::pull($options, 'type'));
        }

        $all = array_merge($parameters, $options);
        return $this->find(
            $identifiers,
            $this->extractFilters($all, false),
            $this->extractSorts($all),
        );
    }

    public function find(array $identifiers, array $filters = [], array $sorts = []): ?Model
    {
        foreach ($filters as $key => $filter) {
            if (!in_array(self::findFilterKey($filter), $identifiers)) {
                continue;
            }
            unset($filters[$key]);
        }

        $filters = array_merge($filters, $this->extractFilters($identifiers, true));
        return $this->selectAction->select($filters, $sorts)->first();
    }

    protected function onModelTypeChanged(): void
    {
        parent::onModelTypeChanged();

        $this->selectAction->setModelType($this->getModelType());
    }
}
