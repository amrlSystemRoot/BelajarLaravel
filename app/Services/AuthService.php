<?php

namespace App\Services;

use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Interfaces\AuthInterface;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthInterface {

    public function signUp(SignUpRequest $request) : Array {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'        => $request->get('name'),
                'email'       => $request->get('email'),
                'password'    => Hash::make($request->get('password')),
            ]);

            DB::commit();

            return [
                'status'   => 'success',
                'message' => 'sign up success',
                'data'     => new UserResource($user),
                'code'     => 200,
            ];

        } catch (\Throwable $th) {

            DB::rollback();

            return [
                'status'   => 'error',
                'message'  => $th->getMessage(),
                'data'     => [],
                'code'     => 500,
            ];
        }
    }

    public function signIn(SignInRequest $request) : Array {
        try {

            $user = User::firstWhere('email', $request->email);

            if (!$user || !Hash::check($request->password, $user->password))
                throw new Error('Sign in failed');

            $user->token = $user->createToken('token')->plainTextToken;

            return [
                'status'        => 'success',
                'message'       => 'sign in success',
                'data'          => new UserResource($user),
                'code'          => 200,
            ];

        } catch (\Throwable $th) {

            return [
                'status'   => 'error',
                'message'  => $th->getMessage(),
                'data'     => [],
                'code'     => 500,
            ];
        }
    }

    public function signOut(Request $request) : Array {
        try {

            $user = $request->user();
            $user->currentAccessToken()->delete();

            return [
                'status'        => 'success',
                'message'       => 'sign in success',
                'data'          => [],
                'code'          => 200,
            ];

        } catch (\Throwable $th) {

            return [
                'status'   => 'error',
                'message'  => $th->getMessage(),
                'data'     => [],
                'code'     => 500,
            ];
        }
    }

    public function update(UpdateRequest $request) : Array {
        try {
            DB::beginTransaction();

            $data = $request->all();

            $request->has('password') ? $data['password'] = Hash::make($data['password']) :null;

            $user = User::findOrfail(Auth::user()->id);
            $user->update($data);

            DB::commit();

            return [
                'status'   => 'success',
                'message' => 'sign up success',
                'data'     => new UserResource($user),
                'code'     => 200,
            ];

        } catch (\Throwable $th) {

            DB::rollback();

            return [
                'status'   => 'error',
                'message'  => $th->getMessage(),
                'data'     => [],
                'code'     => 500,
            ];
        }
    }
}
