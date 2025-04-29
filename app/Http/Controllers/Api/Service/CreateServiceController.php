<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Service\CreateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\Group;

#[Group('Services')]
class CreateServiceController extends Controller
{
    public function __invoke(CreateServiceRequest $request, string $barbershopId)
    {
        $barbershop = $this->findOrFailWithError(Barbershop::class, $barbershopId, 'Barbearia não encontrada');

        $userOwnsBarbershop = Auth::user()
            ->barbershops()
            ->where('id', $barbershop->id)
            ->exists();

        if (!$userOwnsBarbershop) {
            return $this->error('Você não tem permissão para criar serviços nesta barbearia', Response::HTTP_FORBIDDEN);
        }

        $data = $request->validated();
        $data['barbershop_id'] = $barbershop->id;

        $service = $barbershop->services()->create($data);

        return $this->success('Serviço criado com sucesso', Response::HTTP_CREATED, ServiceResource::make($service));
    }
}
