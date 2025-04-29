<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Appointment\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Appointments')]
class UpdateAppointmentController extends Controller
{
    public function __invoke(UpdateAppointmentRequest $request, string $appointmentId)
    {
        $appointment = $this->findOrFailWithError(Appointment::class, $appointmentId, 'Agendamento não encontrado');

        if ($appointment->client_id !== Auth::id()) {
            return $this->error('Você não tem permissão para atualizar este agendamento', Response::HTTP_FORBIDDEN);
        }

        $appointment->update($request->validated());

        return $this->success('Agendamento atualizado com sucesso', Response::HTTP_OK, AppointmentResource::make($appointment));
    }
}
