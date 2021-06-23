<?php

namespace App\Http\Actions\Implementations;

use App\Http\Actions\CreateAction;
use App\Http\Actions\FindAction;
use App\Http\Actions\Implementations\Orders\CreateOrderAction;
use App\Http\Actions\Implementations\Orders\SelectOrderAction;
use App\Http\Actions\SelectAction;
use App\Http\Requests\Implementations\CommentRequest;
use App\Http\Requests\Implementations\OrderRequest;
use App\Models\Banquet;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CreateBanquetAction extends CreateAction
{
    protected CreateOrderAction $createOrderAction;
    protected CreateCommentAction $createCommentAction;

    public function __construct(FindAction $findAction)
    {
        parent::__construct($findAction);

        $selectOrderAction = new SelectOrderAction($this->isSoftDelete());
        $findOrderAction = new FindAction($selectOrderAction);
        $this->createOrderAction = new CreateOrderAction($findOrderAction);

        $selectCommentAction = new SelectAction(Comment::class, false, ['id', 'target_id', 'target_type', 'container_id', 'container_type']);
        $findCommentAction = new FindAction($selectCommentAction);
        $this->createCommentAction = new CreateCommentAction($findCommentAction);
    }

    public function create(array $columns): ?Model
    {
        $comments = Arr::pull($columns, 'comments', []);

        $orders = [];
        foreach (Banquet::getOrderColumnNames() as $orderColumnName) {
            $order = Arr::pull($columns, $orderColumnName);
            if (isset($order)) {
                $orders[$orderColumnName] = $order;
            }
        }

        $instance = parent::create($columns);
        if (!isset($instance)) {
            return null;
        }

        foreach ($orders as $orderColumnName => $order) {
            $orderType = array_search($orderColumnName, Banquet::getOrderColumnNames());
            $order['banquet_id'] = $instance->id;

            $validatedOrder = Validator::validate($order, (new OrderRequest($orderType))->storeRules(false));
            $orderInstance = $this->createOrderAction->execute($validatedOrder, ['type' => $orderType]);
            if (!isset($orderInstance)) {
                return null;
            }
        }

        foreach ($comments as $comment) {
            if (
                !isset($comment['container_id']) ||
                !isset($comment['container_type'])
            ) {
                $comment['container_id'] = $instance->id;
                $comment['container_type'] = $instance->type;
            }
            $validatedComment = Validator::validate($comment, (new CommentRequest())->storeRules(false));
            $this->createCommentAction->execute($validatedComment);
        }

        return $instance->refresh();
    }
}
