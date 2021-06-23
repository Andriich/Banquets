<?php

namespace App\Models\Orders;

use App\Models\BaseDeletableModel;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOrderField extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'product',
    ];

    /**
     * Get product associated with the model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
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
        $query->where('product_id', '=', $this->original['product_id'] ?? $this->product_id);

        return $query;
    }

    protected function setKeysForSelectQuery($query)
    {
        $query->where('order_id', '=', $this->original['order_id'] ?? $this->order_id);
        $query->where('product_id', '=', $this->original['product_id'] ?? $this->product_id);

        return $query;
    }
}
