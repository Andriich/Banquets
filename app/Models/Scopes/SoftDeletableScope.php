<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoftDeletableScope extends SoftDeletingScope
{
    public function apply(Builder $builder, Model $model)
    {
        // uncomment to have only not deleted models
        // parent::apply($builder, $model);
    }
}
