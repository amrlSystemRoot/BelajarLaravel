<?php

namespace App\Services\Interfaces;

use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Requests\Auth\UpdateRequest;
use Illuminate\Http\Request;

interface AuthInterface {
    public function index(Request $request) : Array;
    public function signUp(SignUpRequest $request) : Array;
    public function signIn(SignInRequest $request) : Array;
    public function signOut(Request $request) : Array;
    public function update(UpdateRequest $request) : Array;
}
