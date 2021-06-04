<?php

namespace App\Models\Categories;

use App\Models\BaseDeletableModel;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected $cascadeDeletes = ['products'];

    /**
     * Get products associated with the model.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
