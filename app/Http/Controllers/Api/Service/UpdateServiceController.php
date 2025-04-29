<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\Group;

#[Group('Services')]
class UpdateServiceController extends Controller
{
    public function __invoke(UpdateServiceRequest $request, string $barbershopId, string $serviceId)
    {
        $service = $this->findOrFailWithError(Service::class, $serviceId, 'Serviço não encontrado');

        if ($service->barbershop_id != $barbershopId) {
            return $this->error('Serviço não pertence a esta barbearia', Response::HTTP_FORBIDDEN);
        }

        $userOwnsBarbershop = Auth::user()
            ->barbershops()
            ->where('id', $barbershopId)
            ->exists();

        if (!$userOwnsBarbershop) {
            return $this->error('Você não tem permissão para atualizar serviços nesta barbearia', Response::HTTP_FORBIDDEN);
        }

        $service->update($request->validated());

        return $this->success('Serviço atualizado com sucesso', Response::HTTP_OK, ServiceResource::make($service));
    }
}
