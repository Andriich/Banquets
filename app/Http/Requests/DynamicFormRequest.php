<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;

class DynamicFormRequest extends DataFieldRequest
{
    protected array $actions = ['index', 'show', 'store', 'update', 'destroy'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param string|null $action
     * @return array
     */
    public function rules(?string $action = null): array
    {
        $action = $this->action($action);
        if (!in_array($action, static::actions())) {
            abort(520, self::class . ": no such action $action, for route " . Route::currentRouteName());
        }
        $functionName = $action . 'Rules';
        return $this->$functionName();
    }

    /**
     * Get available action names.
     *
     * @return array
     */
    public function actions(): array
    {
        return $this->actions;
    }

    /**
     * Resolve action.
     *
     * @param string|null $action
     * @return string|null
     */
    public function action(?string $action = null): ?string
    {
        if (empty($action)) {
            $name = Route::currentRouteName();
            foreach (static::actions() as $a) {
                if (!str_ends_with($name, ".$a")) {
                    continue;
                }
                return $a;
            }
        }
        return $action;
    }

    /**
     * Get the validation rules for controller's INDEX method.
     */
    public function indexRules(bool $wrapped = false): array
    {
        return $wrapped ? $this->wrapIntoData([]) : [];
    }

    /**
     * Get the validation rules for controller's SHOW method.
     */
    public function showRules(bool $wrapped = false): array
    {
        return $wrapped ? $this->wrapIntoData([]) : [];
    }


    /**
     * Get the validation rules for controller's STORE method.
     */
    public function storeRules(bool $wrapped = true): array
    {
        return $wrapped ? $this->wrapIntoData([]) : [];
    }

    /**
     * Get the validation rules for controller's UPDATE method.
     */
    public function updateRules(bool $wrapped = true): array
    {
        return $wrapped ? $this->wrapIntoData([]) : [];
    }

    /**
     * Get the validation rules for controller's DESTROY method.
     */
    public function destroyRules(bool $wrapped = false): array
    {
        return $wrapped ? $this->wrapIntoData([]) : [];
    }
}
