<?php

namespace App\Services;

use App\Repositories\BookRepository;

class BookService extends BaseService
{
    public function __construct(BookRepository $repository)
    {
        $this->setRepository($repository);
    }
}
