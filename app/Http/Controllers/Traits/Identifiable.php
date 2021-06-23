<?php

namespace App\Http\Controllers\Traits;

use App\Custom\AttributeExtractionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

trait Identifiable
{
    /**
     * Filterable model class name.
     *
     * @var ?string
     */
    protected ?string $model;

    /**
     * Model's primary key names.
     *
     * @var array
     */
    protected array $primaryKeys = ['id'];

    /**
     * Is soft deleting performed by default.
     *
     * @var bool
     */
    protected bool $softDelete = true;

    /**
     * @return string|null
     */
    public function model(): ?string
    {
        return $this->model;
    }

    /**
     * Get array of names for model's primary keys.
     *
     * @param mixed $except
     * @return array
     */
    public function primaryKeys(mixed $except = null): array
    {
        if (!isset($except)) {
            return $this->primaryKeys;
        }

        if (is_string($except)) {
            $except = [$except];
        }

        $keys = [];
        foreach ($this->primaryKeys as $key) {
            if (in_array($key, $except)) {
                continue;
            }
            $keys[] = $key;
        }
        return $keys;
    }

    /**
     * @return bool
     */
    public function isSoftDelete(): bool
    {
        return $this->softDelete;
    }

    /**
     * Get array of names for model's fillable columns.
     *
     * @return array
     */
    public function fillable(): array
    {
        return (new $this->model)->getFillable();
    }

    /**
     * Get array of model's columns name to type pairs.
     *
     * @return array
     */
    public function types(): array
    {
        $tableName = $this->tableName();
        $tableColumns = $this->tableColumns($tableName);

        $types = [];
        foreach ($tableColumns as $tableColumn) {
            $types[$tableColumn] = Schema::getColumnType($tableName, $tableColumn);
        }

        return $types;
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function tableName(): string
    {
        return (new $this->model)->getTable();
    }

    /**
     * Get table column names.
     *
     * @param string
     * @return array
     */
    public function tableColumns($tableName = null): array
    {
        return Schema::getColumnListing($tableName ?? $this->tableName());
    }

    /**
     * Get array of specified key => value pairs.
     *
     * @param array|object $data
     * @param array $keys
     * @return array
     */
    public function extract(array|object $data, array $keys, bool $throwExtractionException = false): array
    {
        if (empty($data) || empty($keys)) {
            return [];
        }

        $filtered = [];
        foreach ($keys as $key) {
            $value = data_get($data, $key, new AttributeExtractionException($key));
            if ($value instanceof AttributeExtractionException) {
                if ($throwExtractionException) {
                    throw $value;
                }
                continue;
            }

            $filtered[$key] = $value;
        }
        return $filtered;
    }

    /**
     * @param mixed $data
     * @param mixed|null $except
     * @return array
     * @throws AttributeExtractionException
     */
    public function extractIdentifiers(mixed $data, mixed $except = null): array
    {
        if (!isset($data)) {
            throw new AttributeExtractionException(null, 'Not enough values to identify model.');
        }

        if (!is_array($data) && !is_object($data)) {
            if (count($this->primaryKeys($except)) !== 1) {
                throw new AttributeExtractionException(null, 'Not enough values to identify model.');
            }
            return [Arr::first($this->primaryKeys($except)) => $data];
        }

        return $this->extract($data, $this->primaryKeys($except), true);
    }

    /**
     * @param mixed $data
     * @param mixed $alternative
     * @param mixed|null $except
     * @return array
     * @throws AttributeExtractionException
     */
    public function alternativeIdentifiers(mixed $data, mixed $alternative, mixed $except = null): array
    {
        try {
            return $this->extractIdentifiers($data, $except);
        } catch (AttributeExtractionException $exception) {
            return $this->extractIdentifiers($alternative, $except);
        }
    }
}
