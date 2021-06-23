<?php

namespace App\Models\Custom;

use App\Models\Scopes\SoftDeletableScope;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDeletable {
    use SoftDeletes;

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new SoftDeletableScope());
    }
}
