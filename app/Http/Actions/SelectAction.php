<?php

namespace App\Http\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SelectAction extends DynamicAction
{
    public function execute(array $parameters, array $options = [], bool $basedOnType = true): Collection
    {
        if ($this->isTypable()) {
            $this->setModelType(Arr::pull($options, 'type'));
        }

        $all = array_merge($parameters, $options);
        return $this->select(
            $this->extractFilters($all, $basedOnType),
            $this->extractSorts($all),
        );
    }

    public function select(array $filters = [], array $sorts = []): Collection
    {
        // init query builder
        $queryBuilder = $this->model()::select();

        [$modelFilters, $additionalFilters] = $this->splitFilters($filters);
        // append model filters to select query
        $queryBuilder = $this->applyModelFilters($queryBuilder, $modelFilters);

        [$modelSorts, $additionalSorts] = $this->splitSorts($sorts);
        // append model sorts to select query
        $queryBuilder = $this->applyModelSorts($queryBuilder, $modelSorts);

        // retrieve filtered and sorted collection
        $collection = $queryBuilder->get();

        // apply additional filters and sorts on a collection
        $collection = $this->applyAdditionalFilters($collection, $additionalFilters);
        $collection = $this->applyAdditionalSorts($collection, $additionalSorts);

        return $collection;
    }
}
