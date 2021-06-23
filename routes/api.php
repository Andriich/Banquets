<?php

use App\Http\Controllers\DynamicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Implementations\ {
    BanquetController,
    BanquetStateController,
    SpaceController,
    TicketController,
    ServiceController,
    ImperiaMenuController,
    ProductController,
    DiscountController,
    ImperiaRoleController,
    ImperiaUserController,
    CustomerController,
    CustomerFamilyMemberController,
    DatetimeController,
    PeriodController,
    CategoryController,
    OrderController,
    CommentController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::flexibleResources([
    'banquets' => BanquetController::class,
    'banquet-states' => BanquetStateController::class,
    'menus' => ImperiaMenuController::class,
    'products' => ProductController::class,
    'spaces' => SpaceController::class,
    'tickets' => TicketController::class,
    'services' => ServiceController::class,
    'discounts' => DiscountController::class,
    'customers' => CustomerController::class,
    'customer-family-members' => CustomerFamilyMemberController::class,
    'periods' => PeriodController::class,
    'datetimes' => DatetimeController::class,
    'comments' => CommentController::class,
    'roles' => ImperiaRoleController::class,
    'users' => [ImperiaUserController::class, [], function () {
        Route::post('/login', [ImperiaUserController::class, 'login'])
            ->name('login')
            ->withoutMiddleware('auth.token');
    }],
    'orders' => [OrderController::class, ['prefix' => '{type}-orders']],
    'categories' => [CategoryController::class, ['prefix' => '{type}-categories']],
], ['middleware' => 'auth.token']);

Route::group(['middleware' => ['auth.token']], function () {
    Route::get('/routes', function () {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            if (isset($route->action['controller']) && str_contains($route->action['controller'], 'App\\Http\\Controllers\\Implementations\\')) {
                $routes[] = [
                    'route' => $route->uri,
                    'methods' => $route->methods,
                ];
            }
        }
        return ['success' => true, 'routes' => $routes];
    })->name('routes.index');
});

Route::fallback(function () {
    abort(404, 'Not found');
});
