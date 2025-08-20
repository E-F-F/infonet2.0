<?php

namespace Modules\HRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HRMSTrainingParticipantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'staff_id' => $this->hrms_staff_personal_id,
            'staff_name' => $this->personal?->fullName,
            'employee_number' => $this->employee_number,
            'training_participant' => $this->trainingParticipants,
            
        ];
    }
}