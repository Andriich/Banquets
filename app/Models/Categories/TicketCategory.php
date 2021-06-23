<?php

namespace App\Models\Categories;

use App\Models\BaseDeletableModel;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketCategory extends BaseDeletableModel
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

    protected $cascadeDeletes = ['tickets'];

    /**
     * Get tickets associated with the model.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }
}
