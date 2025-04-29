<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Appointment\CreateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\BarbershopOpeningHour;
use App\Models\Service;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

#[Group('Appointments')]
class CreateAppointmentController extends Controller
{
    public function __invoke(CreateAppointmentRequest $request)
    {
        $data = $request->validated();

        $service = $this->findOrFailWithError(Service::class, $data['service_id'], 'Serviço não encontrado');
        $startTime = Carbon::parse($data['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        $openingHours = BarbershopOpeningHour::where('barbershop_id', $data['barbershop_id'])
            ->where('day_of_week', $startTime->dayOfWeek) // 0 (domingo) até 6 (sábado)
            ->first();

        if (!$openingHours) {
            return $this->error('A barbearia está fechada neste dia.', Response::HTTP_FORBIDDEN);
        }

        if (
            $startTime->format('H:i') < $openingHours->opens_at ||
            $endTime->format('H:i') > $openingHours->closes_at
        ) {
            return $this->error('Horário fora do expediente da barbearia.', Response::HTTP_FORBIDDEN);
        }

        $conflict = Appointment::where('barbershop_id', $data['barbershop_id'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime]);
            })
            ->exists();

        if ($conflict) {
            return $this->error('Já existe um agendamento para este horário.', Response::HTTP_CONFLICT);
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'barbershop_id' => $data['barbershop_id'],
            'service_id' => $data['service_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
        ]);

        return $this->success('Agendamento criado com sucesso', Response::HTTP_CREATED, AppointmentResource::make($appointment));
    }
}
