<?php

namespace App\Http\Controllers\Api\Barbershop;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarbershopResource;
use App\Models\Barbershop;
use Illuminate\Http\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Barbershops')]
class GetBarbershopController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(string $id)
    {
        $barbershop = $this->findOrFailWithError(Barbershop::class, $id, 'Barbearia não encontrada');

        return $this->success('Barbearia encontrada com sucesso', Response::HTTP_OK, BarbershopResource::make($barbershop));
    }
}
