<?php

namespace App\Http\Controllers;

use App\Filters\BookFilters;
use App\Http\Requests\FilterBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;

class BookController extends Controller
{
    protected $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(FilterBookRequest $request, BookFilters $filters)
    {
        $result = $this->service->filter($filters);
        return BookResource::collection($result);
    }
}
