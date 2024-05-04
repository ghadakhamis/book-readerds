<?php

namespace App\Services;

use App\Jobs\SendSMS;
use App\Models\Book;
use App\Models\User;
use App\Repositories\ReaderRepository;
use Illuminate\Support\Facades\Auth;

class ReaderService extends BaseService
{
    public function __construct(ReaderRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function create(Array $data, Book $book)
    {
        /** @var User $user */
        $user            = Auth::user();
        $data['book_id'] = $book->id;
        $this->repository->create($data);
        SendSMS::dispatch($user->phone, trans('messages.readed_message'));
    }
}
