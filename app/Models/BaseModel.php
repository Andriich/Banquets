<?php

namespace App\Models;

use App\Models\Scopes\WithTrashedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['targetComments', 'containerComments'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = ['type'];

    /**
     * Accessor for the table type.
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->table;
    }

    /**
     * Get array of model's validation rules.
     *
     * @return array
     * @var bool $forInsert
     */
    public static function getValidationRules($forInsert = false)
    {
        return [];
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     *
     * @param mixed $date
     * @return string|null
     */
    public function toFormattedDate($date)
    {
        if (empty($date)) {
            return null;
        }
        return date_format($date, $this->getDateFormat());
    }

    /**
     * Get all comments for model as a container.
     */
    public
    function containerComments()
    {
        return $this->morphMany(Comment::class, 'container', 'container_type', 'container_id', 'id');
    }

    /**
     * Get all comments for model as a target.
     */
    public
    function targetComments()
    {
        return $this->morphMany(Comment::class, 'target', 'target_type', 'target_id', 'id');
    }

    protected
    static function boot()
    {
        parent::boot();

        self::deleted(function ($model) {
            if ($model instanceof BaseDeletableModel) {
                if ($model->isForceDeleting()) {
                    $model->containerComments()->delete();
                }
            } else {
                $model->containerComments()->delete();
            }
        });
    }
}
