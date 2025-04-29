<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
class RegisterUserController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(RegisterUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        return $this->success('User created successfully', Response::HTTP_CREATED, UserResource::make($user));
    }
}
