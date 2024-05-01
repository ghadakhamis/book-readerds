<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function login(Array $data): User|null
    {
        $user = $this->repository->findBy('email', $data['email']);

        return ($user && Hash::check($data['password'], $user->password))? $user : null;
    }
}