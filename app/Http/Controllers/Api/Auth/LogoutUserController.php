<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
class LogoutUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logout realizado com sucesso!', Response::HTTP_OK);
    }
}
