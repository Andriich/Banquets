<?php

namespace App\Models\Orders;

use App\Models\BaseDeletableModel;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceOrderField extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'service_id',
        'amount',
        'duration',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'service',
    ];

    /**
     * Get service associated with the model.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    /**
     * Get order associated with the model.
     */
    public function order()
    {
        return $this->belongsTo(TicketOrder::class, 'order_id', 'id');
    }

    protected function setKeysForSaveQuery($query)
    {
        $query->where('order_id', '=', $this->original['order_id'] ?? $this->order_id);
        $query->where('service_id', '=', $this->original['service_id'] ?? $this->service_id);

        return $query;
    }

    protected function setKeysForSelectQuery($query)
    {
        $query->where('order_id', '=', $this->original['order_id'] ?? $this->order_id);
        $query->where('service_id', '=', $this->original['service_id'] ?? $this->service_id);

        return $query;
    }
}
