<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'target_id',
        'target_type',
        'container_id',
        'container_type',
    ];

    /**
     * Get the target model.
     */
    public function target()
    {
        return $this->morphTo(__FUNCTION__, 'target_type', 'target_id', 'id');
    }

    /**
     * Get the container model.
     */
    public function container()
    {
        return $this->morphTo(__FUNCTION__, 'container_type', 'container_id', 'id');
    }
}
