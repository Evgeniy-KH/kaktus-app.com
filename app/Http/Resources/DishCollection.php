<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DishCollection extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public $collects = DishResource::class;

    public final function toArray($request): array
    {
        $paginationLinks = $this->getPaginationLinks($request);

        return [
            'data' => $this->collection,
            'links' => $paginationLinks
        ];
    }

    public final function getPaginationLinks($request): array
    {
        $paginationLinks = [];

        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();

        $paginationLinks[] = [
            'url' => $this->url($currentPage - 1),
            'label' => '&laquo; Previous',
            'active' => $currentPage > 1
        ];

        for ($page = 1; $page <= $lastPage; $page++) {
            $paginationLinks[] = [
                'url' => $this->url($page),
                'label' => $page,
                'active' => $page == $currentPage
            ];
        }

        $paginationLinks[] = [
            'url' => $this->url($currentPage + 1),
            'label' => 'Next &raquo;',
            'active' => $currentPage < $lastPage
        ];

        return $paginationLinks;
    }
}


//'links' => [
//                'self' => $this->url($this->currentPage()),
//                'first' => $this->url(1),
//                'last' => $this->url($this->lastPage()),
//                'prev' => $this->previousPageUrl(),
//                'next' => $this->nextPageUrl(),
//            ],
//            'meta' => [
//                'current_page' => $this->currentPage(),
//                'from' => $this->firstItem(),
//                'last_page' => $this->lastPage(),
//                'path' => $this->path(),
//                'per_page' => $this->perPage(),
//                'to' => $this->lastItem(),
//                'total' => $this->total(),
//            ]
