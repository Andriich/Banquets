<?php

namespace App\Models\Orders;

use App\Models\Banquet;
use App\Models\BaseDeletableModel;
use App\Models\Comment;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceOrder extends BaseDeletableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'banquet_id',
        'discount_id',
    ];

    protected $hidden = ['fields'];

    protected $cascadeDeletes = ['fields'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = ['items', 'comments', 'total'];

    /**
     * Get service items associated with the model.
     *
     * @return  array
     */
    public function getItemsAttribute()
    {
        $comments = Comment::select()
            ->where('container_id', '=', $this->banquet_id)
            ->where('container_type', '=', 'banquets')
            ->where('target_type', '=', 'services')
            ->get();

        $fields = $this->fields;
        $items = [];
        foreach ($fields as $field) {
            $vars = $field->service->toArray();
            $vars['amount'] = $field->amount;
            $vars['duration'] = $field->duration;
            $vars['created_at'] = $this->toFormattedDate($field->created_at);
            $vars['updated_at'] = $this->toFormattedDate($field->updated_at);
            $vars['deleted_at'] = $this->toFormattedDate($field->deleted_at);
            $vars['comments'] = [];

            foreach ($comments as $comment) {
                if ($comment->target_id == $vars['id']) {
                    $vars['comments'][] = $comment;
                }
            }
            $items[] = $vars;
        }

        return $items;
    }

    /**
     * Get comments associated with the model.
     *
     * @return  array
     */
    public function getCommentsAttribute() {
        return Comment::select()
            ->where('container_id', '=', $this->banquet_id)
            ->where('container_type', '=', 'banquets')
            ->where('target_id', '=', $this->id)
            ->where('target_type', '=', $this->table)
            ->get();
    }

    /**
     * Get total price of all items with the model.
     *
     * @return float
     */
    public function getTotalAttribute() {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item['once_paid_price'] * $item['amount'];
            $total += $item['hourly_paid_price'] * $item['duration'] / 60 * $item['amount'];
        }

        return round($total, 2);
    }

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'discount',
        'fields',
    ];

    /**
     * Get discount associated with the model.
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }

    /**
     * Get fields associated with the model.
     */
    public function fields()
    {
        return $this->hasMany(ServiceOrderField::class, 'order_id', 'id');
    }

    /**
     * Get banquet associated with the model.
     */
    public function banquet()
    {
        return $this->belongsTo(Banquet::class, 'banquet_id', 'id');
    }
}
