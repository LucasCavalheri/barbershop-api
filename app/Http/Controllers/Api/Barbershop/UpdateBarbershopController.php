<?php

namespace App\Http\Controllers\Api\Barbershop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barbershop\UpdateBarbershopRequest;
use App\Http\Resources\BarbershopResource;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Dedoc\Scramble\Attributes\Group;

#[Group('Barbershops')]
class UpdateBarbershopController extends Controller
{
    public function __invoke(UpdateBarbershopRequest $request, string $id)
    {
        $barbershop = $this->findOrFailWithError(Barbershop::class, $id, 'Barbearia não encontrada');

        $userCanUpdate = Gate::inspect('update', $barbershop);

        if (!$userCanUpdate->allowed()) {
            return $this->error('Você não tem permissão para atualizar essa barbearia', Response::HTTP_FORBIDDEN);
        }

        $data = $request->validated();

        $barbershop->update($data);

        return $this->success('Barbearia atualizada com sucesso', Response::HTTP_OK, BarbershopResource::make($barbershop));
    }
}
