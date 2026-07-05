<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Services\TenantService;
use Exception;

class AuthController extends Controller
{
    protected TenantService $tenantService;
    protected AuthService $authService;

    public function __construct(
        TenantService $tenantService,
        AuthService $authService
    ) {
        $this->tenantService = $tenantService;
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {

            // Find tenant
            $client = $this->tenantService->resolveTenant($request->email);

            // Switch database
            $this->tenantService->connect($client);

            // Authenticate user
            $data = $this->authService->login(
                $request->email,
                $request->password
            );

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $data['token'],
                'user' => $data['user'],
            ]);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);

        }
    }


    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);

        }
    }
}