<?php

namespace App\Models\Categories;

use App\Models\BaseDeletableModel;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpaceCategory extends BaseDeletableModel
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

    protected $cascadeDeletes = ['spaces'];

    /**
     * Get spaces associated with the model.
     */
    public function spaces()
    {
        return $this->hasMany(Space::class, 'category_id', 'id');
    }
}
