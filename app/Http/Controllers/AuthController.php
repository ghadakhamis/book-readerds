<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->service->login($request->validated());

        return $user? 
            response()->json(['user' => new UserResource($user), 'token' => $user->generateToken()], Response::HTTP_OK)
            : response()->json(['message' => trans('auth.failed'), 'errors' => ['email' => trans('auth.failed')]], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}