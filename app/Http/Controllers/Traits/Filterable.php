<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait Filterable
{
    use Identifiable;

    /**
     * Filters that can be applied directly to model's table.
     *
     * @var array|null
     */
    protected ?array $modelFilters = null;

    /**
     * Additional filters that should be applied on a collection.
     *
     * @var array
     */
    protected array $additionalFilters = [];

    /**
     * Applied filters.
     *
     * @var array
     */
    protected array $appliedFilters = [];

    /**
     * Get available filters.
     *
     * @return array
     */
    public function getAvailableFilters(): array
    {
        return array_merge($this->getModelFilters(), $this->getAdditionalFilters());
    }

    /**
     * Get model's filters.
     *
     * @return array
     */
    public function getModelFilters(): array
    {
        if ($this->modelFilters === null) {
            return $this->tableColumns();
        }
        return $this->modelFilters;
    }

    /**
     * Get additional filters that should be applied on a collection.
     *
     * @return array
     */
    public function getAdditionalFilters(): array
    {
        return $this->additionalFilters;
    }

    /**
     * Get currently applied filters.
     *
     * @return array
     */
    public function getAppliedFilters(): array
    {
        return $this->appliedFilters;
    }

    public static function findFilter(?array $filters, string $column, ?array $default = null): ?array {
        if (empty($filters)) {
            return $default;
        }

        foreach ($filters as $filter) {
            if ($filter[0] === $column) {
                return $filter;
            }
        }
        return $default;
    }

    public static function findFilterKey(?array $filter, mixed $default = null): mixed {
        if (empty($filter)) {
            return $default;
        }

        return Arr::first($filter, null, $default);
    }

    public static function findFilterOperator(?array $filter, mixed $default = null): mixed {
        if (empty($filter)) {
            return $default;
        }

        return data_get($filter, count($filter) - 2, $default);
    }

    public static function findFilterValue(?array $filter, mixed $default = null): mixed {
        if (empty($filter)) {
            return $default;
        }

        return Arr::last($filter, null, $default);
    }

    /**
     * Get only those filters that are specified in a second argument.
     *
     * @return array
     */
    public static function limitFilters(array $requestedFilters, array $availableFilters): array
    {
        if (empty($availableFilters)) {
            return [];
        }

        $limitedFilters = [];
        foreach ($requestedFilters as $filter) {
            if (!is_array($filter) || !in_array($filter[0], $availableFilters)) {
                continue;
            }
            $limitedFilters[] = $filter;
        }
        return $limitedFilters;
    }

    /**
     * Get model and additional filters.
     *
     * @param array $filters
     * @return array
     */
    public function splitFilters(array $filters): array
    {
        if (empty($filters)) {
            return [[], []];
        }

        return [
            $this->limitFilters($filters, $this->getModelFilters()),
            $this->limitFilters($filters, $this->getAdditionalFilters()),
        ];
    }

    /**
     * Get array of filters for columns.
     *
     * @param array $data
     * @param bool $basedOnType
     * @return array
     */
    public function extractFilters(array $data, bool $basedOnType = false): array
    {
        if (empty($data)) {
            return [];
        }

        if ($basedOnType) {
            $types = $this->types();
        }

        $conditions = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (isset($value['min'])) {
                    $conditions[] = [$key, '>=', $value['min']];
                }
                if (isset($value['max'])) {
                    $conditions[] = [$key, '<=', $value['max']];
                }
                if (isset($value['only'])) {
                    $ins = preg_split('(,)', $value['only']);
                    $conditions[] = [$key, 'in', $ins];
                }
                if (isset($value['except'])) {
                    $outs = preg_split('(,)', $value['except']);
                    $conditions[] = [$key, 'not in', $outs];
                }
            } else {
                if ($key === 'trashed') {
                    if ($value === 'only') {
                        $conditions[] = ['deleted_at', '!=', null];
                    } else if ($value === 'with') {
//                        $conditions[] = ['deleted_at', '=', null];
                    } else if ($value === 'without') {
                        $conditions[] = ['deleted_at', '=', null];
                    }
                } else if (
                    isset($types) &&
                    isset($types[$key]) &&
                    $types[$key] === 'string'
                ) {
                    if (in_array($key, $this->primaryKeys())) {
                        $conditions[] = [$key, '=', $value];
                    } else {
                        $conditions[] = [$key, 'like', '%' . $value . '%'];
                    }
                } else {
                    $conditions[] = [$key, '=', $value];
                }
            }
        }
        return $conditions;
    }

    public function applyModelFilters(Collection|Builder $data, array $filters): Collection|Builder
    {
        if (empty($filters)) {
            return $data;
        }

        if ($data instanceof Collection) {
            return $data->filter(function ($item) use ($filters) {
                return $this->isMatchingFilters($item, $filters, false);
            });
        }

        foreach ($filters as $filter) {
            if (is_array($filter)) {
                if (count($filter) === 2) {
                    $data->where($filter[0], $filter[1]);
                } else if (count($filter) === 3) {
                    if ($filter[1] === 'in') {
                        $data->whereIn($filter[0], $filter[2]);
                    } else if ($filter[1] === 'not in') {
                        $data->whereNotIn($filter[0], $filter[2]);
                    } else {
                        $data->where($filter[0], $filter[1], $filter[2]);
                    }
                }
            }
        }

        $this->appliedFilters = array_merge($this->appliedFilters, $filters);
        return $data;
    }

    public function applyAdditionalFilters(Collection|Builder $data, array $filters): Collection|Builder
    {
        // implement additional filtering logic
        return $data;
    }

    /**
     * Determine if specified data matches where condition.
     *
     * @param array|object $data
     * @param array $filter [key, condition, value/[values]]
     * @param bool $strict
     * @return bool
     */
    public static function isMatchingFilter(array|object $data, array $filter, bool $strict = false): bool
    {
        if (empty($filter)) {
            return true;
        }

        $dataValue = data_get($data, self::findFilterKey($filter));
        $filterOperator = self::findFilterOperator($filter, '=');
        $filterValue = self::findFilterValue($filter);

        switch ($filterOperator) {
            case 'in':
                return in_array($dataValue, Arr::wrap($filterValue));

            case 'not in':
                return !in_array($dataValue, Arr::wrap($filterValue));

            case 'like':
                $filterValue = strtolower($filterValue);
                $length = strlen($filterValue);
                if ($length > 2) {
                    $filterValue = substr($filterValue, 1, $length - 2);
                }
                return str_contains(strtolower($dataValue), strtolower($filterValue));

            case '=':
                return $strict ? $dataValue === $filterValue : $dataValue == $filterValue;

            case '!=':
                return $strict ? $dataValue !== $filterValue : $dataValue != $filterValue;

            case '>=':
                return $dataValue >= $filterValue;

            case '<=':
                return $dataValue <= $filterValue;

            case '>':
                return $dataValue > $filterValue;

            case '<':
                return $dataValue < $filterValue;
        }
        return true;
    }

    /**
     * Determine if specified data matches where conditions.
     *
     * @param array|object $data
     * @param array $filters [[key, condition, value/[values]], ...]
     * @param bool $strict
     * @return bool
     */
    public static function isMatchingFilters(array|object $data, array $filters, bool $strict = false): bool
    {
        $filters = Arr::wrap($filters);
        foreach ($filters as $whereCondition) {
            if (!static::isMatchingFilter($data, $whereCondition, $strict)) {
                return false;
            }
        }
        return true;
    }
}
