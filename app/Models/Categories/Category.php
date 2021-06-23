<?php

namespace App\Models\Categories;

use App\Models\BaseDeletableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseDeletableModel
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
    ];

    /**
     * Get available types of categories.
     *
     * @return string[]
     */
    public static function getModelTypes()
    {
        return array_keys(self::getModels());
    }

    /**
     * Get available type models.
     *
     * @return array
     */
    public static function getModels()
    {
        return [
            'menu' => MenuCategory::class,
            'product' => ProductCategory::class,
            'space' => SpaceCategory::class,
            'ticket' => TicketCategory::class,
            'service' => ServiceCategory::class,
            'discount' => DiscountCategory::class,
        ];
    }

    /**
     * Get table name for specified type.
     *
     * @return string|null
     * @var string $type
     */
    public static function getModelTableName($type)
    {
        if (in_array($type, self::getModelTypes())) {
            return "{$type}_categories";
        }
        return null;
    }
}
