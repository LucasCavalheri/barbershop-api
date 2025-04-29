<?php

namespace App\Http\Controllers\Api\OpeningHour;

use App\Http\Controllers\Controller;
use App\Models\BarbershopOpeningHour;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Opening Hours')]
class DeleteOpeningHourController extends Controller
{
    public function __invoke(string $barbershopId, string $openingHourId)
    {
        $openingHour = $this->findOrFailWithError(BarbershopOpeningHour::class, $openingHourId, 'Horário de funcionamento não encontrado');

        if ($openingHour->barbershop_id != $barbershopId) {
            return $this->error('Horário não pertence a esta barbearia', Response::HTTP_FORBIDDEN);
        }

        $openingHour->delete();

        return $this->success('Horário deletado com sucesso', Response::HTTP_NO_CONTENT);
    }
}
