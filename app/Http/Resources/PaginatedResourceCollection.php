<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatedResourceCollection extends BaseResourceCollection
{
    protected ?LengthAwarePaginator $paginator;

    public function __construct($resource, LengthAwarePaginator $paginator = null)
    {
        parent::__construct($resource);
        $this->paginator = $paginator;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $paginator = $this->paginator ?? $this->collection->paginate($request);

        return [
            'pagination' => [
                'size' => (int)$paginator->perPage(),
                'pages' => (int)$paginator->lastPage(),
                'items' => (int)$paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'prev' => $paginator->previousPageUrl(),
                'self' => $paginator->url($paginator->currentPage()),
                'next' => $paginator->nextPageUrl(),
                'last' => $paginator->url($paginator->lastPage()),
            ],
            'data' => array_values($paginator->all()),
        ];
    }
}
