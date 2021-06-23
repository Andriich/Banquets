<?php

namespace App\Http\Actions\Other;

use App\Models\Space;
use DateTime;
use Illuminate\Support\Collection;

class LoadSpaceIntervalsAction
{
    protected array $appliedFilters = [];

    /**
     * @return array
     */
    public function getAppliedFilters(): array
    {
        return $this->appliedFilters;
    }

    /**
     * Loads Space's business intervals for the specified period of time.
     *
     * @param Space $instance
     * @param DateTime|string|null $begDatetime
     * @param DateTime|string|null $endDatetime
     * @return Collection
     */
    public function execute(Space $instance, DateTime|string|null $begDatetime, DateTime|string|null $endDatetime): Collection
    {
        if (!($instance instanceof Space) || (empty($begDatetime) && empty($endDatetime))) {
            return new Collection();
        }

        $builder = $instance->intervals();
        if (isset($begDatetime) && isset($endDatetime)) {
            // captures all intervals that intersect the specified interval
            $builder->where(function ($builder) use ($begDatetime, $endDatetime) {
                $builder->where(function ($builder) use ($begDatetime, $endDatetime) {
                    // starts between/on beginning and end
                    $builder->where('beg_datetime', '>=', $begDatetime) // starts after/on beginning
                    ->where('beg_datetime', '<=', $endDatetime); // starts before/on ending
                })->orWhere(function ($builder) use ($begDatetime, $endDatetime) {
                    // ends between/on beginning and end
                    $builder->where('end_datetime', '>=', $begDatetime) // ends after/on beginning
                    ->where('end_datetime', '<=', $endDatetime); // ends before/on ending
                })->orWhere(function ($builder) use ($begDatetime, $endDatetime) {
                    // starts before/on beginning and ends after/on ending
                    $builder->where('beg_datetime', '<=', $begDatetime) // starts before/on beginning
                    ->where('end_datetime', '>=', $endDatetime); // ends after/on ending
                });
            });

            $this->appliedFilters = [
                ['beg_datetime', 'between', [$begDatetime, $endDatetime]],
                'or',
                ['end_datetime', 'between', [$begDatetime, $endDatetime]]
            ];
        } else if (isset($begDatetime)) {
            // captures all intervals starting from specified datetime
            $builder->where(function ($builder) use ($begDatetime) {
                $builder->where(function ($builder) use ($begDatetime) {
                    $builder->where('end_datetime', '>=', $begDatetime) // ends after/on beginning
                    ->where('beg_datetime', '<=', $begDatetime); // begins before/on beginning
                })->orWhere('beg_datetime', '>=', $begDatetime); // begins after/on beginning
            });

            $this->appliedFilters = [
                ['beg_datetime', '>=', $begDatetime]
            ];
        }

        $intervals = $builder->get();
        foreach ($intervals as $interval) {
            $interval->makeHidden('banquet');
            $interval->banquet_id = $interval->banquet->banquet_id ?? null;
        }

        return $intervals;
    }
}
