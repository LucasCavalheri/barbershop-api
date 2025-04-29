<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\Group;

#[Group('Services')]
class DeleteServiceController extends Controller
{
    public function __invoke(string $barbershopId, string $serviceId)
    {
        $service = $this->findOrFailWithError(Service::class, $serviceId, 'Serviço não encontrado');

        // Verifica se o serviço pertence à barbearia da URL
        if ($service->barbershop_id != $barbershopId) {
            return $this->error('Serviço não pertence a esta barbearia', Response::HTTP_FORBIDDEN);
        }

        // Verifica se a barbearia pertence ao usuário autenticado
        $userOwnsBarbershop = Auth::user()
            ->barbershops()
            ->where('id', $barbershopId)
            ->exists();

        if (!$userOwnsBarbershop) {
            return $this->error('Você não tem permissão para deletar serviços nesta barbearia', Response::HTTP_FORBIDDEN);
        }

        $service->delete();

        return $this->success('Serviço deletado com sucesso', Response::HTTP_OK);
    }
}
