<?php

namespace App\Models\Orders;

use App\Models\Banquet;
use App\Models\BaseDeletableModel;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpaceOrderField extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'space_id',
        'beg_datetime',
        'end_datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['banquet'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'space',
    ];

    /**
     * Get space associated with the model.
     */
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'id');
    }

    /**
     * Get order associated with the model.
     */
    public function order()
    {
        return $this->belongsTo(SpaceOrder::class, 'order_id', 'id');
    }

    public function banquet()
    {
        return $this->hasOneThrough(
            Banquet::class,
            SpaceOrder::class,
            'id',
            'id',
            'order_id',
            'banquet_id'
        )->select('banquet_id')->withOnly([]);
    }

    protected function setKeysForSaveQuery($query)
    {
        $query->where('order_id', '=', $this->original['order_id'] ?? $this->order_id);
        $query->where('space_id', '=', $this->original['space_id'] ?? $this->space_id);

        return $query;
    }

    protected function setKeysForSelectQuery($query)
    {
        $query->where('order_id', '=', $this->original['order_id'] ?? $this->order_id);
        $query->where('space_id', '=', $this->original['space_id'] ?? $this->space_id);

        return $query;
    }
}
