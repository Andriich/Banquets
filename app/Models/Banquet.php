<?php

namespace App\Models;

use App\Models\Orders\Order;
use App\Models\Orders\ProductOrder;
use App\Models\Orders\ServiceOrder;
use App\Models\Orders\SpaceOrder;
use App\Models\Orders\TicketOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banquet extends BaseDeletableModel
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
        'advance_amount',
        'beg_datetime',
        'end_datetime',
        'state_id',
        'creator_id',
        'customer_id',
    ];

    protected $cascadeDeletes = ['ticketOrder', 'spaceOrder', 'productOrder', 'serviceOrder'];

    /**
     * Get array of model's order column names.
     *
     * @return array
     */
    public static function getOrderColumnNames() {
        $orderColumnNames = [];
        foreach (Order::getModelTypes() as $orderType) {
            $orderColumnNames[$orderType] = $orderType . '_order';
        }
        return $orderColumnNames;
    }

    /**
     * Get array of model's order column names.
     *
     * @return array
     */
    public static function getOrderRelationshipNames() {
        $orderRelationshipNames = [];
        foreach (Order::getModelTypes() as $orderType) {
            $orderRelationshipNames[$orderType] = $orderType . 'Order';
        }
        return $orderRelationshipNames;
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = ['comments', 'total'];

    public function getCommentsAttribute()
    {
        return $this->containerComments;
    }

    public function getTotalAttribute()
    {
        $total = 0.0;
        if (!empty($this->spaceOrder)) {
            $total += $this->spaceOrder->total;
        }
        if (!empty($this->serviceOrder)) {
            $total += $this->serviceOrder->total;
        }
        if (!empty($this->ticketOrder)) {
            $total += $this->ticketOrder->total;
        }
        if (!empty($this->productOrder)) {
            $total += $this->productOrder->total;
        }

        return round($total, 2);
    }

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'state',
        'creator',
        'customer',
        'spaceOrder',
        'ticketOrder',
        'serviceOrder',
        'productOrder',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total' => 'float',
        'advance_amount' => 'float',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the state associated with the model.
     */
    public function state()
    {
        return $this->belongsTo(BanquetState::class, 'state_id', 'id');
    }

    /**
     * Get the user associated with the model.
     */
    public function creator()
    {
        return $this->belongsTo(ImperiaUser::class, 'creator_id', 'id')
            ->without('role')
            ->select(['id', 'name']);
    }

    /**
     * Get the customer associated with the model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get the space order associated with the model.
     */
    public function spaceOrder()
    {
        return $this->hasOne(SpaceOrder::class, 'banquet_id', 'id');
    }

    /**
     * Get the ticket order associated with the model.
     */
    public function ticketOrder()
    {
        return $this->hasOne(TicketOrder::class, 'banquet_id', 'id');
    }

    /**
     * Get the ticket order associated with the model.
     */
    public function serviceOrder()
    {
        return $this->hasOne(ServiceOrder::class, 'banquet_id', 'id');
    }

    /**
     * Get the product order associated with the model.
     */
    public function productOrder()
    {
        return $this->hasOne(ProductOrder::class, 'banquet_id', 'id');
    }
}
