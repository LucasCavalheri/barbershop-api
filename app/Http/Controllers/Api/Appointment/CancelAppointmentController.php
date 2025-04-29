<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Appointments')]
class CancelAppointmentController extends Controller
{
    public function __invoke(string $appointmentId)
    {
        $appointment = $this->findOrFailWithError(Appointment::class, $appointmentId, 'Agendamento não encontrado');

        if ($appointment->user_id !== Auth::id()) {
            return $this->error('Você não tem permissão para cancelar este agendamento', Response::HTTP_FORBIDDEN);
        }

        $appointment->update(['status' => 'canceled']);

        return $this->success('Agendamento cancelado com sucesso', Response::HTTP_OK, AppointmentResource::make($appointment));
    }
}
