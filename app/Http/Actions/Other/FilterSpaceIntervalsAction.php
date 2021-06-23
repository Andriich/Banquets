<?php

namespace App\Http\Actions\Other;

use App\Http\Controllers\Traits\Filterable;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FilterSpaceIntervalsAction
{
    protected LoadSpaceIntervalsAction $loadIntervals;

    protected array $appliedFilters = [];

    /**
     * @return array
     */
    public function getAppliedFilters(): array
    {
        return $this->appliedFilters;
    }

    /**
     * FilterSpaceIntervalsAction constructor.
     *
     * @param LoadSpaceIntervalsAction $loadIntervals
     */
    public function __construct(LoadSpaceIntervalsAction $loadIntervals)
    {
        $this->loadIntervals = $loadIntervals;
    }

    /**
     * Loads Space's business intervals for the specified period of time.
     *
     * @param Space $instance
     * @param array $filters
     * @return Collection
     */
    public function execute(Space $instance, array $filters = []): Collection
    {
        if (empty($instance) || empty($filters)) {
            return new Collection();
        }

        $begDatetimeFilter = Filterable::findFilter($filters, 'beg_datetime');
        $endDatetimeFilter = Filterable::findFilter($filters, 'end_datetime');
        $intervals = $this->loadIntervals->execute(
            $instance,
            Filterable::findFilterValue($begDatetimeFilter, Carbon::now()->format('Y-m-d H:i:s')),
            Filterable::findFilterValue($endDatetimeFilter)
        );
        $this->appliedFilters = $this->loadIntervals->getAppliedFilters();

        $banquetIdFilter = Filterable::findFilter($filters, 'banquet_id');
        if (!empty($banquetIdFilter)) {
            $intervals = $intervals->filter(function ($interval) use ($banquetIdFilter) {
                return Filterable::isMatchingFilters($interval, [$banquetIdFilter], false);
            });

            $this->appliedFilters[] = $banquetIdFilter;
        }
        return $intervals;
    }
}
