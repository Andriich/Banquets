<?php

namespace App\Models;

use App\Models\Categories\SpaceCategory;
use App\Models\Orders\SpaceOrderField;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Space extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'number',
        'floor',
        'price',
        'period_id',
        'category_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'period',
        'category',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get period associated with the model.
     */
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    /**
     * Get category associated with the model.
     */
    public function category()
    {
        return $this->belongsTo(SpaceCategory::class, 'category_id', 'id');
    }

    public function intervals()
    {
        return $this->hasMany(SpaceOrderField::class, 'space_id', 'id')
            ->without('space')
            ->with('banquet')
            ->select(['order_id', 'space_id', 'beg_datetime', 'end_datetime']);
    }
}
