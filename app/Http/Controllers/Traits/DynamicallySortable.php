<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait DynamicallySortable
{
    use DynamicallyIdentifiable;

    /**
     * Sorts that can be applied directly to model's table.
     *
     * @var array|null
     */
    protected ?array $modelSorts = null;

    /**
     * Get sorts that can be applied directly to model's table.
     *
     * @return array
     */
    public function getModelSorts(): array
    {
        if ($this->modelSorts === null) {
            return $this->tableColumns();
        }
        return $this->modelSorts;
    }

    /**
     * Sorts that have custom logic.
     *
     * @var array
     */
    protected array $additionalSorts = [];

    /**
     * Get sorts that have custom logic.
     *
     * @return array
     */
    public function getAdditionalSorts(): array
    {
        return $this->additionalSorts;
    }

    /**
     * Applied sorts.
     *
     * @var array
     */
    protected array $appliedSorts = [];

    /**
     * Get currently applied sorts.
     *
     * @return array
     */
    public function getAppliedSorts(): array
    {
        return $this->appliedSorts;
    }

    /**
     * Get only those sorts that are specified in a second argument.
     *
     * @return array
     */
    protected function limitSorts(array $requestedSorts, array $availableSorts)
    {
        if (empty($availableSorts)) {
            return [];
        }

        $limitedSorts = [];
        foreach ($requestedSorts as $key => $order) {
            if (!in_array($key, $availableSorts)) {
                continue;
            }
            $limitedSorts[$key] = $order;
        }
        return $limitedSorts;
    }

    /**
     * Get model and additional filters.
     *
     * @return array
     */
    protected function extractSorts(array $data)
    {
        if (empty($data)) {
            return [[], []];
        }

        $sorts = $this->orderByConditions($data);
        return [
            $this->limitSorts($sorts, $this->getModelSorts()),
            $this->limitSorts($sorts, $this->getAdditionalSorts()),
        ];
    }

    protected function applySorts(Collection|Builder $data, array $sorts): Collection|Builder
    {
        $modelSorts = $this->limitFilters($sorts, $this->getModelSorts());
        $additionalSorts = $this->limitFilters($sorts, $this->getAdditionalSorts());

        $data = $this->applyModelSorts($data, $modelSorts);
        $data = $this->applyAdditionalSorts($data, $additionalSorts);

        return $data;
    }

    protected function applyModelSorts(Collection|Builder $data, array $sorts): Collection|Builder
    {
        if (empty($sorts)) {
            return $data;
        }

        foreach ($sorts as $key => $order)
        {
            $data->orderBy($key, $order);
        }
        $this->appliedSorts = $sorts;
        return $data;
    }

    protected function applyAdditionalSorts(Collection|Builder $data, array $sorts): Collection|Builder
    {
        // implement additional sorting logic
        return $data;
    }

    /**
     * Get array of order by conditions for columns.
     *
     * @param array $data
     * @return array
     */
    public function orderByConditions(array $data): array
    {
        if (empty($data) || empty($data['sort'])) {
            return [];
        }

        $sort = [];
        if (is_string($data['sort'])) {
            $columns = preg_split('(\s*,\s*)', $data['sort']);
            foreach ($columns as $column) {
                $length = strlen($column);
                if ($length === 0) {
                    continue;
                }

                if (preg_match('(-\S{1,})', $column)) {
                    $sort[substr($column, 1, $length - 1)] = 'desc';
                } else {
                    $sort[$column] = 'asc';
                }
            }
        }

        return $sort;
    }
}
