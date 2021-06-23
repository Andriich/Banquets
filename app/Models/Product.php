<?php

namespace App\Models;

use App\Models\Categories\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseDeletableModel
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
        'price',
        'weight',
        'menu_id',
        'category_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'category',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'weight' => 'float',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the menu associated with the model.
     */
    public function menu()
    {
        return $this->belongsTo(ImperiaMenu::class, 'menu_id', 'id');
    }

    /**
     * Get the category associated with the model.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
}
