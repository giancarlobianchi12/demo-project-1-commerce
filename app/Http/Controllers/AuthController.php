<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid user or password'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(new JsonResource(Auth::user()));
    }

    public function me(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(new JsonResource(Auth::user()));
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['error' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
