<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReaderRequest;
use App\Models\Book;
use App\Services\ReaderService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReaderController extends Controller
{
    protected $service;

    public function __construct(ReaderService $service)
    {
        $this->service = $service;
    }

    public function store(StoreReaderRequest $request, Book $book): JsonResponse
    {
        $this->service->create($request->validated(), $book);

        return response()->json(['message' => trans('messages.book_readed')], Response::HTTP_CREATED);
    }
}
