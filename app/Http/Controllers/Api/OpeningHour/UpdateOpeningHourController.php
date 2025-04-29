<?php

namespace App\Http\Controllers\Api\OpeningHour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OpeningHour\UpdateOpeningHourRequest;
use App\Http\Resources\OpeningHourResource;
use App\Models\BarbershopOpeningHour;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Opening Hours')]
class UpdateOpeningHourController extends Controller
{
    public function __invoke(UpdateOpeningHourRequest $request, string $barbershopId, string $openingHourId)
    {
        $openingHour = $this->findOrFailWithError(BarbershopOpeningHour::class, $openingHourId, 'Horário de funcionamento não encontrado');

        if ($openingHour->barbershop_id != $barbershopId) {
            return $this->error('Horário não pertence a esta barbearia', Response::HTTP_FORBIDDEN);
        }

        $openingHour->update($request->validated());

        return $this->success('Horário atualizado com sucesso', Response::HTTP_OK, OpeningHourResource::make($openingHour));
    }
}
