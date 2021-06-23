<?php

namespace App\Models\Categories;

use App\Models\BaseDeletableModel;
use App\Models\ImperiaMenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuCategory extends BaseDeletableModel
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

    protected $cascadeDeletes = ['menus'];

    /**
     * Get menus associated with the model.
     */
    public function menus()
    {
        return $this->hasMany(ImperiaMenu::class, 'menu_id', 'id');
    }
}
