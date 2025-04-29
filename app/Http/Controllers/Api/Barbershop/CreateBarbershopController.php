<?php

namespace App\Http\Controllers\Api\Barbershop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barbershop\CreateBarbershopRequest;
use App\Http\Resources\BarbershopResource;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\Group;

#[Group('Barbershops')]
class CreateBarbershopController extends Controller
{
    public function __invoke(CreateBarbershopRequest $request)
    {
        $data = $request->validated();

        $barbershop = Barbershop::create([
            ...$data,
            'user_id' => Auth::id(),
        ]);

        return $this->success('Barbearia criada com sucesso', Response::HTTP_CREATED, BarbershopResource::make($barbershop));
    }
}
