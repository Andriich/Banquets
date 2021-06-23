<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Actions\Implementations\CreateCommentAction;
use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\CommentRequest;
use App\Models\Comment;

class CommentController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = Comment::class;

    /**
     * Model's primary keys.
     *
     * @var string[]
     */
    protected array $primaryKeys = ['id', 'container_id', 'container_type', 'target_id', 'target_type'];

    public function __construct(CommentRequest $request)
    {
        parent::__construct($request);
        $this->createAction = new CreateCommentAction($this->findAction);
    }

    public function identify(mixed $id, bool $prefixed = false, bool $validated = false, mixed $except = null): array
    {
        if ($this->request->action() === 'show') {
            return $this->extractIdentifiers($id, array_diff($this->primaryKeys(), ['id']));
        }

        return parent::identify($id, $prefixed, $validated, $except);
    }
}
