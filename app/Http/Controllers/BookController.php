<?php

namespace App\Http\Controllers;

use App\Services\BookService;

class BookController extends Controller
{
    protected $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }
}
