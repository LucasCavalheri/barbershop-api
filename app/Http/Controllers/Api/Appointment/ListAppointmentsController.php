<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Appointments')]
class ListAppointmentsController extends Controller
{
    public function __invoke()
    {
        $appointments = Auth::user()->appointments()->with('service')->latest()->get();

        return $this->success('Agendamentos listados com sucesso', Response::HTTP_OK, AppointmentResource::collection($appointments));
    }
}
