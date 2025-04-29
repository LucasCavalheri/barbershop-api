<?php

namespace App\Http\Controllers\Api\Barbershop;

use App\Http\Controllers\Controller;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;

#[Group('Barbershops')]
class DeleteBarbershopController extends Controller
{
    public function __invoke(string $id)
    {
        $barbershop = $this->findOrFailWithError(Barbershop::class, $id, 'Barbearia não encontrada');

        $userCanDelete = Gate::inspect('delete', $barbershop);

        if (!$userCanDelete->allowed()) {
            return $this->error('Você não tem permissão para deletar essa barbearia', Response::HTTP_FORBIDDEN);
        }

        // Verificar se há agendamentos ativos (pendentes ou confirmados) no futuro
        $activeAppointments = $barbershop->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_time', '>=', Carbon::now())
            ->exists();

        if ($activeAppointments) {
            return $this->error('Não é possível deletar a barbearia porque há agendamentos ativos. Cancele os agendamentos primeiro.', Response::HTTP_CONFLICT);
        }

        $barbershop->delete();

        return $this->success('Barbearia deletada com sucesso', Response::HTTP_OK);
    }
}
