<?php

namespace App\Http\Actions\Implementations;

use App\Http\Actions\Other\FilterSpaceIntervalsAction;
use App\Http\Actions\Other\LoadSpaceIntervalsAction;
use App\Http\Actions\SelectAction;
use App\Models\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SelectSpacesAction extends SelectAction
{
    protected array $additionalFilters = ['banquet_id', 'beg_datetime', 'end_datetime'];

    protected FilterSpaceIntervalsAction $intervalsAction;

    public function __construct(bool $softDelete = true, array $primaryKeys = ['id'])
    {
        parent::__construct(Space::class, $softDelete, $primaryKeys);
        $this->intervalsAction = new FilterSpaceIntervalsAction(new LoadSpaceIntervalsAction());
    }

    public function applyAdditionalFilters(Builder|Collection $data, array $filters): Collection|Builder
    {
        if ($data instanceof Builder) {
            return $data;
        }

        foreach ($data as $instance) {
            $instance->intervals = $this->intervalsAction->execute($instance, $filters);
        }

        // appending banquet filters to applied
        $this->appliedFilters = array_merge(
            $this->appliedFilters,
            $this->intervalsAction->getAppliedFilters()
        );

        return $data;
    }
}
