<?php

namespace App\Http\Controllers\Api\OpeningHour;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpeningHourResource;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Opening Hours')]
class ListOpeningHoursController extends Controller
{
    public function __invoke(string $barbershopId)
    {
        $barbershop = $this->findOrFailWithError(Barbershop::class, $barbershopId, 'Barbearia não encontrada');

        return $this->success('Horários de funcionamento listados com sucesso', Response::HTTP_OK, OpeningHourResource::collection($barbershop->openingHours()->get()));
    }
}
