<?php

namespace App\Http\Actions\Implementations;

use App\Http\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

class CreateCommentAction extends CreateAction
{
    public function create(array $columns): ?Model
    {
        $instance = parent::create($columns);

        if (!isset($instance)) {
            return null;
        }

        $identifiers = $this->extractIdentifiers($instance, 'id');
        return $this->findAction->find($identifiers, [], ['id' => 'desc']);
    }
}
