<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'birthdate',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'familyMembers',
    ];

    public function familyMembers()
    {
        return $this->hasMany(CustomerFamilyMember::class, 'customer_id', 'id');
    }
}
