<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        // ambil data  
        $data = $request->all();
        
        // validasi data
        $val = Validator::make($data, 
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8'
            ]
        );

        // cek error dan kirim ke front end 
        if ($val->fails()) {
            return response()->json([
                'status' => 'error',
                'massage' => $val->errors()
            ], 422);
        }

        // encrip password
        $data['password'] = Hash::make($request->password);
        
        // simpan ke table di db
        $user = User::create($data);

        // menampilkan user jika berhasil
        return response()->json(
            [
                'status' => 'success',
                'masssage' => 'berhasil menambah user',
                'data' => $user
            ],200
        );
    }

    public function login(Request $request) 
    {
        // mengambil data email dari database
        $user = User::where('email', $request->email)->first();

        // validasi data
        $val = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]
        );

        // cek error dan kirim ke front end 
        if ($val->fails()) {
            return response()->json([
                'status' => 'error',
                'massage' => $val->errors()
            ], 422);
        }

        // cek ada email dan cek verifikasi password
        if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'massage' => 'User tidak ditemukan'
                ],400
            );
        }

        // membuat token untuk session
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
                'status' => 'success',
                'massage' => 'berhasil login',
                'data' => ['user' => $user, 'token' => $token]
            ],200
        );
    }

    public function tes() {
        return "Berhasil Login";
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            "massage" => "Anda berhasil log out"
        ], 200);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $val = Validator::make($data, 
            [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]
        );

        $data['password'] = Hash::make($request->password);

        if ($val->fails()) {
            return response()->json([
                'status' => 'error',
                'massage' => $val->errors()
            ], 422);
        }

        $user = Auth::user();
        // $user->update($data);

        return response()->json(
            [
                'status' => 'success',
                'masssage' => 'berhasil edit data',
                'data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password
                ]
            ],200
        );
    }
}