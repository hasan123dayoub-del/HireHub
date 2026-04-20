<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => new UserResource($user)
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);

        if (!$result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'status' => 'success',
            'data'   => new UserResource($result['user']),
            'token'  => $result['token']
        ]);
    }
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout Successful'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $this->authService->deleteAccount($request->user());

        return response()->json([
            'status'  => 'success',
            'message' => 'User account deleted successfully'
        ]);
    }
}
