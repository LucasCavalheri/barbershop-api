<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
class LoginUserController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(LoginUserRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return $this->error('Credenciais inválidas', Response::HTTP_UNAUTHORIZED);
        }

        /** @var \App\Models\User */
        $user = Auth::user();
        $token = $user->createToken('access_token')->plainTextToken;

        return $this->success('Login realizado com sucesso', Response::HTTP_OK, [
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }
}
