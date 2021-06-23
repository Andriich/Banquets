<?php

namespace App\Http\Controllers;

use App\Custom\AttributeExtractionException;
use App\Http\Actions\CreateAction;
use App\Http\Actions\DeleteAction;
use App\Http\Actions\FindAction;
use App\Http\Actions\RestoreAction;
use App\Http\Actions\SelectAction;
use App\Http\Actions\UpdateAction;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Traits\Filterable;
use App\Http\Controllers\Traits\Identifiable;
use App\Http\Controllers\Traits\Sortable;
use App\Http\Requests\DynamicFormRequest;
use App\Http\Resources\PaginatedResourceCollection;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DynamicController extends BaseController
{
    use Identifiable, Filterable, Sortable;

    /**
     * Dynamic form request request.
     *
     * @var DynamicFormRequest
     */
    protected DynamicFormRequest $request;

    /**
     * Get dynamic form request
     *
     * @return DynamicFormRequest
     */
    public function request(): DynamicFormRequest
    {
        return $this->request;
    }

    /**
     * SelectAction instance.
     *
     * @var SelectAction
     */
    protected SelectAction $selectAction;

    /**
     * FindAction instance.
     *
     * @var FindAction
     */
    protected FindAction $findAction;

    /**
     * Creator instance.
     *
     * @var CreateAction
     */
    protected CreateAction $createAction;

    /**
     * UpdateAction instance.
     *
     * @var UpdateAction
     */
    protected UpdateAction $updateAction;

    /**
     * DeleteAction instance.
     *
     * @var DeleteAction
     */
    protected DeleteAction $deleteAction;

    /**
     * RestoreAction instance.
     *
     * @var RestoreAction
     */
    protected RestoreAction $restoreAction;

    public function __construct(DynamicFormRequest $request)
    {
        $this->request = $request;
        $this->setUpActions($this->createSelectAction());
    }

    protected function createSelectAction(): SelectAction {
        return new SelectAction($this->model(), $this->isSoftDelete(), $this->primaryKeys());
    }

    protected function setUpActions(SelectAction $selectAction) {
        $this->selectAction = $selectAction;
        $this->findAction = new FindAction($this->selectAction);
        $this->createAction = new CreateAction($this->findAction);
        $this->restoreAction = new RestoreAction($this->findAction);
        $this->updateAction = new UpdateAction($this->findAction, $this->restoreAction);
        $this->deleteAction = new DeleteAction($this->findAction);
    }

    /**
     * Get filtered and paginated collection of models.
     *
     * @return Response
     */
    public function index(): Response
    {
        $collection = $this->allModels($this->request->getData(false, false));
        if ($collection->count() === 0) {
            abort(404, 'Not found');
        }
        return $this->toResponse($this->request, new PaginatedResourceCollection($collection));
    }

    /**
     * Get one specific model by it's primary keys.
     *
     * @param mixed $id
     * @return Response
     */
    public function show(mixed $id = null): Response
    {
        $instance = $this->findModel(
            $this->identify($id),
            $this->request->getData(false, false),
            $this->request->getData(false, false)
        );
        if (!isset($instance)) {
            abort(404, 'Not found');
        }

        return $this->toResponse($this->request, new JsonResource($instance));
    }

    /**
     * Insert new model's record into the database.
     *
     * @return Response
     */
    public function store(): Response
    {
        $instance = $this->createModel(
            $this->request->getData(true),
            $this->request->getData(true, true)
        );
        if (!isset($instance)) {
            abort(520, 'Error while creating record.');
        }

        return $this->toResponse($this->request(), new JsonResource($instance), true, 201);
    }

    /**
     * Update model's record in the database.
     *
     * @param mixed $id
     * @return Response
     */
    public function update(mixed $id = null): Response
    {
        $instance = $this->updateModel(
            $this->identify($id, true, true),
            $this->request->getData(true),
            $this->request->getData(false, false)
        );
        if (!isset($instance)) {
            abort(520, 'Error while updating record.');
        }

        return $this->toResponse($this->request(), new JsonResource($instance));
    }

    /**
     * DeleteAction model's record from the database.
     *
     * @param mixed $id
     * @return Response
     */
    public function destroy(mixed $id = null): Response
    {
        $success = $this->destroyModel(
            $this->identify($id, true, true),
            $this->request->getData(true),
            $this->request->getData(false, false)
        );
        if (!$success) {
            abort(520, 'Error while deleting record from the database.');
        }

        return $this->toResponse($this->request(), []);
    }

    public function identify(mixed $id, bool $prefixed = false, bool $validated = false, mixed $except = null): array
    {
        try {
            return $this->alternativeIdentifiers($id, $this->request->getData($prefixed, $validated), $except);
        } catch (AttributeExtractionException $exception) {
            throw new HttpException(400, $exception->getMessage());
        }
    }

    public function allModels(array $parameters, array $options = [], bool $basedOnType = true): Collection
    {
        $collection = $this->selectAction->execute($parameters, $options, $basedOnType);

        $this->appliedFilters = $this->selectAction->getAppliedFilters();
        $this->appliedSorts = $this->selectAction->getAppliedSorts();
        return $collection;
    }

    public function findModel(array $identifiers, array $parameters, array $options = []): ?Model
    {
        $instance = $this->findAction->execute($identifiers, $parameters, $options);

        $this->appliedFilters = $this->findAction->getAppliedFilters();
        $this->appliedSorts = $this->findAction->getAppliedSorts();
        return $instance;
    }

    public function createModel(array $columns, array $options = []): ?Model
    {
        return $this->createAction->execute($columns, $options);
    }

    public function updateModel(array $identifiers, array $parameters, array $options = []): ?Model
    {
        return $this->updateAction->execute($identifiers, $parameters, $options);
    }

    public function destroyModel(array $identifiers, array $parameters, array $options = []): bool
    {
        return $this->deleteAction->execute($identifiers, $parameters, $options);
    }

    public function restoreModel(array $identifiers, array $parameters, array $options = []): ?Model
    {
        return $this->restoreAction->execute($identifiers, $parameters, $options);
    }

    /**
     * Get array of values for response.
     *
     * @param Request $request
     * @param bool $success
     * @param PaginatedResourceCollection|JsonResource|array
     * @return array
     */
    public function toResponseArray(Request $request, bool $success, $data = []): array
    {
        $array = ['success' => $success];

        // displaying applied sorting parameters, should be removed on release
        $array['sorts'] = [];
        foreach ($this->appliedSorts as $columnName => $sortOrder) {
            $array['sorts'][] = [$columnName, $sortOrder];
        }

        // displaying applied filtering parameters, should be removed on release
        $array['filters'] = $this->appliedFilters;

        if ($data instanceof PaginatedResourceCollection) {
            $array = array_merge(
                $array,
                $data->toArray($request),
            );
        } else if ($data instanceof JsonResource) {
            $array['data'] = $data->toArray($request);
        } else if (is_array($data)) {
            $array = array_merge(
                $array,
                $data,
            );
        }

        return $array;
    }

    /**
     * Get array of values for exception response.
     *
     * @param Exception $exception
     * @return array
     */
    public function toExceptionArray(Exception $exception): array
    {
        if ($exception instanceof ValidationException) {
            $message = '';
            foreach ($exception->errors() as $errors) {
                foreach ($errors as $error) {
                    $message .= $error . ' ';
                }
            }

            return [
                'success' => false,
                'message' => $message,
                'errors' => $exception->errors(),
            ];
        }

        return [
            'success' => false,
            'message' => $exception->getMessage(),
            'errors' => [
                $exception->getMessage(),
            ],
        ];
    }

    /**
     * Convert to response.
     *
     * @param Request $request
     * @param Exception|array|JsonResource|ResourceCollection $data
     * @param bool $success
     * @param int $code
     * @return Response
     */
    public function toResponse(Request $request, array|JsonResource|ResourceCollection|Exception $data, bool $success = true, int $code = 200): Response
    {
        if ($data instanceof Exception) {
            if ($data instanceof ValidationException) {
                $code = (int)$data->status;
            } else {
                $code = (int)$data->getCode();
            }

            if (!in_array($code, array_keys(Response::$statusTexts))) {
                $code = 520; // unknown error status code
            }

            return \Illuminate\Support\Facades\Response::make(
                $this->toExceptionArray($data),
                $code,
            );
        }

        return \Illuminate\Support\Facades\Response::make(
            $this->toResponseArray($request, $success, $data),
            $code,
        );
    }
}
