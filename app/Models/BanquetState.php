<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BanquetState extends BaseDeletableModel
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
     * Get banquets associated with the model.
     */
    public function banquets()
    {
        return $this->hasMany(Banquet::class, 'state_id', 'id');
    }
}
