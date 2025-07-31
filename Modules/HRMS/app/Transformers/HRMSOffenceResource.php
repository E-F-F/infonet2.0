<?php

namespace Modules\HRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HRMSOffenceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'branch_code' => $this->branch?->code,
            'description' => $this->description,
            'staff_id' => $this->hrms_staff_id,
            'staff_name' => $this->staff?->personal?->fullName,
            'issue_date' => $this->issue_date,
            'offence_type_id' => $this->hrms_offence_type_id,
            'offence_type_name' => $this->offenceType?->name,
            'action_taken_id' => $this->hrms_offence_action_taken_id,
            'action_taken_name' => $this->actionTaken?->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
