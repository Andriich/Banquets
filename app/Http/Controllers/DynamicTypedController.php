<?php

namespace App\Http\Controllers;

use App\Http\Actions\SelectAction;
use App\Http\Controllers\Traits\Typable;
use App\Http\Requests\DynamicTypedFormRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class DynamicTypedController extends DynamicController
{
    use Typable;

    public function __construct(DynamicTypedFormRequest $request, ?string $type = null)
    {
        parent::__construct($request);

        if (isset($type)) {
            $this->setModelType($type);
        }
    }

    protected function createSelectAction(): SelectAction
    {
        return new SelectAction($this->model(), $this->isSoftDelete(), $this->primaryKeys(), true, $this->getModels());
    }

    public function index($type = null): Response
    {
        $this->setModelType($type);
        return parent::index();
    }

    public function show($type = null, $id = null): Response
    {
        $this->setModelType($type);
        return parent::show($id);
    }

    public function store($type = null): Response
    {
        $this->setModelType($type);
        return parent::store();
    }

    public function update($type = null, $id = null): Response
    {
        $this->setModelType($type);
        return parent::update($id);
    }

    public function destroy($type = null, $id = null): Response
    {
        $this->setModelType($type);
        return parent::destroy($id);
    }

    public function allModels(array $parameters, array $options = [], bool $basedOnType = true): Collection
    {
        $options['type'] = $this->getModelType();
        return parent::allModels($parameters, $options, $basedOnType);
    }

    public function findModel(array $identifiers, array $parameters, array $options = []): ?Model
    {
        $options['type'] = $this->getModelType();
        return parent::findModel($identifiers, $parameters, $options);
    }

    public function createModel(array $columns, array $options = []): ?Model
    {
        $options['type'] = $this->getModelType();
        return parent::createModel($columns, $options);
    }

    public function updateModel(array $identifiers, array $parameters, array $options = []): ?Model
    {
        $options['type'] = $this->getModelType();
        return parent::updateModel($identifiers, $parameters, $options);
    }

    public function destroyModel(array $identifiers, array $parameters, array $options = []): bool
    {
        $options['type'] = $this->getModelType();
        return parent::destroyModel($identifiers, $parameters, $options);
    }

    public function restoreModel(array $identifiers, array $parameters, array $options = []): ?Model
    {
        $options['type'] = $this->getModelType();
        return parent::restoreModel($identifiers, $parameters, $options);
    }

    protected function onModelTypeChanged(): void
    {
        $this->model = data_get($this->getModels(), $this->getModelType());
        if ($this->request instanceof DynamicTypedFormRequest) {
            $this->request->setModelType($this->getModelType());
        }
    }
}
