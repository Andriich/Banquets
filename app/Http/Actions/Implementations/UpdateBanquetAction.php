<?php

namespace App\Http\Actions\Implementations;

use App\Http\Actions\DeleteAction;
use App\Http\Actions\FindAction;
use App\Http\Actions\Implementations\Orders\CreateOrderAction;
use App\Http\Actions\Implementations\Orders\SelectOrderAction;
use App\Http\Actions\Implementations\Orders\UpdateOrderAction;
use App\Http\Actions\RestoreAction;
use App\Http\Actions\SelectAction;
use App\Http\Actions\UpdateAction;
use App\Http\Requests\Implementations\CommentRequest;
use App\Http\Requests\Implementations\OrderRequest;
use App\Models\Banquet;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateBanquetAction extends UpdateAction
{
    protected CreateOrderAction $createOrderAction;
    protected UpdateOrderAction $updateOrderAction;
    protected DeleteAction $deleteOrderAction;
    protected CreateCommentAction $createCommentAction;
    protected UpdateAction $updateCommentAction;
    protected DeleteAction $deleteCommentAction;

    public function __construct(FindAction $findAction, RestoreAction $restoreAction)
    {
        parent::__construct($findAction, $restoreAction);

        $selectOrderAction = new SelectOrderAction($this->isSoftDelete());
        $findOrderAction = new FindAction($selectOrderAction);
        $restoreOrderAction = new RestoreAction($findOrderAction);
        $this->createOrderAction = new CreateOrderAction($findOrderAction);
        $this->updateOrderAction = new UpdateOrderAction($findOrderAction, $restoreOrderAction);
        $this->deleteOrderAction = new DeleteAction($findOrderAction);

        $selectCommentAction = new SelectAction(Comment::class, false, ['id', 'target_id', 'target_type', 'container_id', 'container_type']);
        $findCommentAction = new FindAction($selectCommentAction);
        $restoreCommentAction = new RestoreAction($findCommentAction);
        $this->createCommentAction = new CreateCommentAction($findCommentAction);
        $this->updateCommentAction = new UpdateAction($findCommentAction, $restoreCommentAction);
        $this->deleteCommentAction = new DeleteAction($findCommentAction);
    }

    public function update(?Model $instance, array $columns): ?Model
    {


        $instance = parent::update($instance, $columns);
        if (!isset($instance)) {
            return null;
        }

        $newComments = Arr::pull($columns, 'comments', []);

        $orderColumnNames = Banquet::getOrderColumnNames();
        $orderRelationshipNames = Banquet::getOrderRelationshipNames();

        $newOrders = [];
        foreach ($orderColumnNames as $orderType => $orderColumnName) {
            $order = Arr::pull($columns, $orderColumnName);
            if (isset($order)) {
                $newOrders[$orderColumnName] = $order;
            }
        }

        $oldOrders = [];
        foreach ($orderRelationshipNames as $orderType => $orderRelationshipName) {
            if (isset($instance->$orderRelationshipName)) {
                $oldOrders[$orderColumnNames[$orderType]] = $instance->$orderRelationshipName;
            }
        }

        if (isset($newOrders)) {
            foreach ($newOrders as $orderColumnName => $newOrder) {
                $orderType = array_search($orderColumnName, $orderColumnNames);

                if (isset($oldOrders[$orderColumnName])) {
                    // updating old order
                    if (!empty($oldOrders[$orderColumnName])) {
                        $newOrder['id'] = $oldOrders[$orderColumnName]->id;
                    }
                    $newOrder['banquet_id'] = $instance->id;

                    $validatedOrder = Validator::validate($newOrder, (new OrderRequest($orderType))->updateRules(false));
                    $success = $this->updateOrderAction->execute(['id' => $oldOrders[$orderColumnName]->id], $validatedOrder, ['type' => $orderType]);
                    if (!$success) {
                        return null;
                    }
                } else {
                    // creating new order
                    $newOrder['banquet_id'] = $instance->id;

                    $validatedOrder = Validator::validate($newOrder, (new OrderRequest($orderType))->storeRules(false));
                    $orderInstance = $this->createOrderAction->execute($validatedOrder, ['type' => $orderType]);
                    if (!isset($orderInstance)) {
                        return null;
                    }
                }
            }
        }

        if (isset($newComments)) {
            foreach ($newComments as $key => $newComment) {
                if (
                    !isset($newComment['container_id']) ||
                    !isset($newComment['container_type'])
                ) {
                    $newComments[$key]['container_id'] = $instance->id;
                    $newComments[$key]['container_type'] = $instance->type;
                }
            }

            // updating and deleting old comments
            foreach ($instance->comments as $oldComment) {
                $updateComment = null;
                foreach ($newComments as $newComment) {
                    if (empty($newComment['id'])) {
                        continue;
                    }

                    if (
                        $oldComment->id == $newComment['id'] &&
                        $oldComment->target_id == $newComment['target_id'] &&
                        $oldComment->target_type == $newComment['target_type'] &&
                        $oldComment->container_id == $newComment['container_id'] &&
                        $oldComment->container_type == $newComment['container_type']
                    ) {
                        $updateComment = $newComment;
                        break;
                    }
                }

                if (isset($updateComment)) {
                    // updating old comment
                    $validatedComment = Validator::validate($updateComment, (new CommentRequest())->updateRules(false));
                    $commentInstance = $this->updateCommentAction->update($oldComment, $validatedComment);
                    if (!isset($commentInstance)) {
                        return null;
                    }
                } else {
                    // deleting old comment
                    $success = $this->deleteCommentAction->delete($oldComment);
                    if (!$success) {
                        return null;
                    }
                }
            }

            // creating new comments
            foreach ($newComments as $newComment) {
                if (!isset($newComment['id'])) {
                    // creating new comment
                    $validatedComment = Validator::validate($newComment, (new CommentRequest())->storeRules(false));
                    $commentInstance = $this->createCommentAction->create($validatedComment);
                    if (!isset($commentInstance)) {
                        return null;
                    }
                }
            }
        }
        return $instance->refresh();
    }
}
