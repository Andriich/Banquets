<?php

namespace App\Models\Categories;

use App\Models\BaseDeletableModel;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceCategory extends BaseDeletableModel
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

    protected $cascadeDeletes = ['services'];

    /**
     * Get services associated with the model.
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'id');
    }
}
