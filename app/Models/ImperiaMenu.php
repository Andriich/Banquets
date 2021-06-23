<?php

namespace App\Models;

use App\Models\Categories\MenuCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImperiaMenu extends BaseDeletableModel
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
        'period_id',
        'category_id',
    ];

    protected $cascadeDeletes = ['products'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'period',
        'category',
        'products',
    ];

    /**
     * Get the period associated with the model.
     */
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    /**
     * Get the category associated with the model.
     */
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id', 'id');
    }

    /**
     * Get the products associated with the model.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'menu_id', 'id');
    }

}
