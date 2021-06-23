<?php

namespace App\Http\Actions\Implementations\Orders;

use App\Http\Actions\FindAction;
use App\Http\Actions\RestoreAction;
use App\Http\Actions\UpdateAction;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateOrderAction extends UpdateAction
{
    public function __construct(FindAction $findAction, RestoreAction $restoreAction)
    {
        parent::__construct($findAction, $restoreAction);
    }

    public function update(?Model $instance, array $columns): ?Model
    {
        $newItems = Arr::pull($columns, 'items');

        $instance = parent::update($instance, $columns);
        if (!isset($instance)) {
            return null;
        }

        if (isset($newItems)) {
            // there were no items but now there is
            if (empty($instance->items) && !empty($newItems)) {
                $newFields = Order::toFields($instance, $newItems);
                foreach ($newFields as $newField) {
                    if (!$newField->save()) {
                        return null;
                    }
                }
            } // there was at least one item but now there isn't
            else if (empty($newItems) && !empty($instance->items)) {
                $itemIdKey = $this->getModelType() . '_id';
                foreach ($instance->fields as $oldField) {
                    $fieldsTableName = Order::getModelFieldTableName($this->getModelType());
                    $success = DB::table($fieldsTableName)
                        ->where('order_id', $oldField->order_id)
                        ->where($itemIdKey, $oldField->$itemIdKey)
                        ->delete();

                    if (!$success) {
                        return null;
                    }
                }
            } // there was at least one item and now there is at least one
            else {
                $newFields = Order::toFields($instance, $newItems);

                $itemIdKey = $this->getModelType() . '_id';
                foreach ($instance->fields as $oldField) {
                    $isThere = false;
                    foreach ($newFields as $newField) {
                        if ($oldField->$itemIdKey == $newField->$itemIdKey) {
                            $isThere = true;
                        }
                    }

                    if (!$isThere) {
                        $fieldsTableName = Order::getModelFieldTableName($this->getModelType());
                        $success = DB::table($fieldsTableName)
                            ->where('order_id', $oldField->order_id)
                            ->where($itemIdKey, $oldField->$itemIdKey)
                            ->delete();

                        if (!$success) {
                            return null;
                        }
                    }
                }

                foreach ($newFields as $newField) {
                    $isThere = false;
                    foreach ($instance->fields as $oldField) {
                        if ($oldField->$itemIdKey == $newField->$itemIdKey) {
                            $oldField->fill($newField->attributesToArray());
                            $updates = [];
                            foreach ($oldField->getFillable() as $attrributeName) {
                                if ($oldField->isDirty($attrributeName)) {
                                    $updates[$attrributeName] = $oldField->$attrributeName;
                                }
                            }
                            if (!empty($updates)) {
                                $fieldsTableName = Order::getModelFieldTableName($this->getModelType());
                                $success = DB::table($fieldsTableName)
                                    ->where('order_id', $oldField->order_id)
                                    ->where($itemIdKey, $oldField->$itemIdKey)
                                    ->update($updates);

                                if (!$success) {
                                    return null;
                                }
                            }

                            $isThere = true;
                        }
                    }

                    if (!$isThere) {
                        if (!$newField->save()) {
                            return null;
                        }
                    }
                }
            }
        }

        return $this->findAction->find($this->extractIdentifiers($instance));
    }
}
