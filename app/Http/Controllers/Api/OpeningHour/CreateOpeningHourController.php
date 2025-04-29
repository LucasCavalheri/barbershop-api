<?php

namespace App\Http\Controllers\Api\OpeningHour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OpeningHour\CreateOpeningHourRequest;
use App\Http\Resources\OpeningHourResource;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Opening Hours')]
class CreateOpeningHourController extends Controller
{
    public function __invoke(CreateOpeningHourRequest $request, string $barbershopId)
    {
        $barbershop = $this->findOrFailWithError(Barbershop::class, $barbershopId, 'Barbearia não encontrada');

        $openingHour = $barbershop->openingHours()->create($request->validated());

        return $this->success('Horário de funcionamento criado com sucesso', Response::HTTP_CREATED, OpeningHourResource::make($openingHour));
    }
}
