<?php

namespace App\Models\Orders;

use App\Models\BaseDeletableModel;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketOrderField extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'ticket_id',
        'amount',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'ticket',
    ];

    /**
     * Get ticket associated with the model.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
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
        $query->where('ticket_id', '=', $this->original['ticket_id'] ?? $this->ticket_id);

        return $query;
    }

    protected function setKeysForSelectQuery($query)
    {
        $query->where('order_id', '=', $this->original['order_id'] ?? $this->order_id);
        $query->where('ticket_id', '=', $this->original['ticket_id'] ?? $this->ticket_id);

        return $query;
    }
}
