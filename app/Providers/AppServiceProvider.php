<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'banquets' => 'App\Models\Banquet',
            'banquet_states' => 'App\Models\BanquetState',
            'periods' => 'App\Models\Period',
            'datetimes' => 'App\Models\Datetime',
            'discounts' => 'App\Models\Discount',
            'imperia_menus' => 'App\Models\ImperiaMenu',
            'products' => 'App\Models\Product',
            'services' => 'App\Models\Service',
            'spaces' => 'App\Models\Space',
            'tickets' => 'App\Models\Ticket',
            'customers' => 'App\Models\Customer',
            'customer_family_members' => 'App\Models\CustomerFamilyMember',
            'imperia_users' => 'App\Models\ImperiaUser',
            'imperia_roles' => 'App\Models\ImperiaRole',
            'comments' => 'App\Models\Comment',
            'category' => 'App\Models\Categories\Category',
            'product_category' => 'App\Models\Categories\Category',
            'service_category' => 'App\Models\Categories\ServiceCategory',
            'space_category' => 'App\Models\Categories\SpaceCategory',
            'ticket_category' => 'App\Models\Categories\TicketCategory',
            'discount_category' => 'App\Models\Categories\DiscountCategory',
        ]);

        Route::macro('flexibleResource', function (string $slug, string $controller, array $attributes = [], \Closure $closure = null) {
            Arr::set($attributes, 'prefix', Arr::get($attributes, 'prefix', $slug));
            Arr::set($attributes, 'as', Arr::get($attributes, 'as', "$slug."));

            Route::group($attributes, function () use ($controller) {
                Route::get('/', [$controller, 'index'])
                    ->name('index');

                Route::get('/{id}', [$controller, 'show'])
                    ->name('show');

                Route::post('/', [$controller, 'store'])
                    ->name('store');

                Route::patch('/{id?}', [$controller, 'update'])
                    ->name('update');

                Route::delete('/{id?}', [$controller, 'destroy'])
                    ->name('destroy');
            });

            if (isset($closure)) {
                Route::group($attributes, $closure);
            }
        });

        Route::macro('flexibleResources', function (array $resources, array $attributes = []) {
            foreach ($resources as $slug => $resource) {
                if (!is_array($resource)) {
                    Route::flexibleResource($slug, $resource, $attributes);
                    continue;
                }

                Route::flexibleResource($slug, $resource[0], array_merge($attributes, $resource[1] ?? []), $resource[2] ?? null);
            }
        });

        Collection::macro('paginate', function (Request $request, ?int $perPage = null, ?int $page = null, string $pageName = 'page[number]'): LengthAwarePaginator {
            $options = $request->query('page');
            if (isset($options)) {
                if (isset($options['number'])) {
                    $page = $options['number'];
                }
                if (isset($options['size'])) {
                    $perPage = $options['size'];
                }
            }

            $perPage = $perPage ?: $this->count();
            $perPage = $perPage > 0 ? $perPage : 1;
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            $paginator = new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
            $paginator->appends('page[size]', $perPage);
            return $paginator;
        });
    }
}
