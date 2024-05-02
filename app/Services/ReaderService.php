<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\ReaderRepository;

class ReaderService extends BaseService
{
    public function __construct(ReaderRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function create(Array $data, Book $book)
    {
        $data['book_id'] = $book->id;
        $this->repository->create($data);
    }
}
