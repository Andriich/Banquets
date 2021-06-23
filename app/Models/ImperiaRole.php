<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImperiaRole extends BaseDeletableModel
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
        'can_read',
        'can_insert',
        'can_modify',
        'is_owner',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'can_read' => 'boolean',
        'can_insert' => 'boolean',
        'can_modify' => 'boolean',
        'is_owner' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get users associated with the role.
     */
    public function users()
    {
        return $this->hasMany(ImperiaUser::class, 'role_id', 'id');
    }
}
