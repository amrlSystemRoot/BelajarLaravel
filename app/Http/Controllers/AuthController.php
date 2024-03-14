<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Services\Interfaces\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authService = $authInterface;
    }

    public function response(Array $response) {
        return response()->json(
            [
                'status'   => $response['status'],
                'messsage' => $response['message'],
                'data'     => $response['data']
            ], $response['code'] ?? 500
        );
    }

    public function signUp(SignUpRequest $request)
    {
        return $this->response($this->authService->signUp($request));
    }

    public function signIn(SignInRequest $request)
    {
        return $this->response($this->authService->signIn($request));
    }

    public function signOut(Request $request)
    {
        return $this->response($this->authService->signOut($request));
    }

    public function update(UpdateRequest $request)
    {
        return $this->response($this->authService->update($request));
    }
}
