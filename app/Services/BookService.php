<?php

namespace App\Services;

use App\Filters\QueryFilters;
use App\Repositories\BookRepository;

class BookService extends BaseService
{
    public function __construct(BookRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function filter(QueryFilters $filters)
    {
        return $this->repository->filters($filters);
    }
}
