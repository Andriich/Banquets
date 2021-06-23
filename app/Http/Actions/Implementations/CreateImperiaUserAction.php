<?php

namespace App\Http\Actions\Implementations;

use App\Http\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

class CreateImperiaUserAction extends CreateAction
{
    public function execute(array $parameters, array $options = []): ?Model
    {
        $parameters['api_toekn'] = hash('sha256', uniqid());
        return parent::execute($parameters, $options);
    }
}
