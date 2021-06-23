<?php

namespace App\Http\Controllers\Implementations;

use App\Http\Actions\Implementations\CreateImperiaUserAction;
use App\Http\Controllers\DynamicController;
use App\Http\Requests\Implementations\ImperiaUserRequest;
use App\Models\ImperiaUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ImperiaUserController extends DynamicController
{
    /**
     * Controller's model class name.
     *
     * @var ?string
     */
    protected ?string $model = ImperiaUser::class;


    public function __construct(ImperiaUserRequest $request)
    {
        parent::__construct($request);
        $this->createAction = new CreateImperiaUserAction($this->findAction);
    }

    /**
     * Get the user with api_token from name and password.
     *
     * @return Response
     */
    public function login()
    {
        $data = $this->request()->validated();

        $identifiers = $this->extract($data, ['name', 'password']);
        if (isset($data['api_token'])) {
            $identifiers = $this->extract($data, ['api_token']);
        }

        $user = $this->findModel($identifiers, []);
        if (!isset($user)) {
            abort(401, 'Invalid credentials');
        }

        return $this->toResponse($this->request(), new JsonResource($user));
    }
}
