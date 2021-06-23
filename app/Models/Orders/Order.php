<?php

namespace App\Models\Orders;

use App\Models\BaseDeletableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends BaseDeletableModel
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

    /**
     * Get available types of categories.
     *
     * @return string[]
     */
    public static function getModelTypes()
    {
        return array_keys(self::getModels());
    }

    /**
     * Get available types of categories.
     *
     * @return string[]
     */
    public static function getModels()
    {
        return [
            'space' => SpaceOrder::class,
            'ticket' => TicketOrder::class,
            'service' => ServiceOrder::class,
            'product' => ProductOrder::class,
        ];
    }

    /**
     * Get available types of categories.
     *
     * @return string[]
     */
    public static function getModelFields()
    {
        return [
            'space' => SpaceOrderField::class,
            'ticket' => TicketOrderField::class,
            'service' => ServiceOrderField::class,
            'product' => ProductOrderField::class,
        ];
    }

    /**
     * Get table name for specified type.
     *
     * @return string|null
     * @var string $type
     */
    public static function getModelTableName($type)
    {
        if (in_array($type, self::getModelTypes())) {
            return "{$type}_orders";
        }
        return null;
    }

    /**
     * Get table name for specified type.
     *
     * @return string|null
     * @var string $type
     */
    public static function getModelFieldTableName($type)
    {
        if (in_array($type, self::getModelTypes())) {
            return "{$type}_order_fields";
        }
        return null;
    }

    /**
     * Convert items to Fields
     *
     * @param Model $order
     * @param array $items
     * @return array
     */
    public static function toFields(Model $order, array $items): array
    {
        $fields = [];

        $type = array_search(get_class($order), self::getModels());

        $itemIdKey = $type . '_id';
        $orderId = data_get($order, 'id');
        foreach ($items as $item) {
            $itemId = data_get($item, 'id');
            if (isset($itemId) && isset($orderId)) {
                $field = new (Order::getModelFields()[$type]);
                $field->fill($item);
                $field->order_id = $orderId;
                $field->$itemIdKey = $itemId;

                $fields[] = $field;
            }
        }
        return $fields;
    }
}
