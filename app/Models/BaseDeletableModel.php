<?php

namespace App\Models;

use App\Models\Custom\SoftDeletable;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class BaseDeletableModel extends BaseModel
{
    use SoftDeletable, CascadeSoftDeletes;

    /**
     * Array of relation names that should be deleted with the current model.
     * @var array
     */
    protected $cascadeDeletes = [];

    protected static function boot()
    {
        parent::boot();

        static::restored(function ($model) {
            $model->runCascadingRestores();
        });
    }

    protected function scopeActive($query)
    {
        return $query->where('deleted_at', '=', 'null');
    }

    protected function scopeNonactive($query)
    {
        return $query->where('deleted_at', '!=', 'null');
    }

    /**
     * Run the cascading soft restore for this model.
     *
     * @return void
     */
    protected function runCascadingRestores()
    {
        foreach ($this->getActiveCascadingDeletes() as $relationship) {
            $this->cascadeSoftRestore($relationship);
        }
    }

    /**
     * Cascade delete the given relationship on the given mode.
     *
     * @param string $relationship
     * @return void
     */
    public function cascadeSoftRestore(string $relationship)
    {
        $trashed = $this->$relationship()->onlyTrashed()->get();
        $lastDeletedAt = $trashed->max('deleted_at');

        foreach ($trashed as $item) {
            if ($item instanceof BaseDeletableModel) {
                // should restore only last deleted items, so if some of them
                // was deleted (earlier) on purpose then they will stay deleted
                if ($item->deleted_at == $lastDeletedAt) {
                    $item->restore();
                }
            }
        }
    }

}
