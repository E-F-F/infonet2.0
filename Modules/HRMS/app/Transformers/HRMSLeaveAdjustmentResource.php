<?php

namespace Modules\HRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HRMSLeaveAdjustmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'leave_type_id' => $this->hrms_leave_type_id,
            'leave_type_name' => $this->leaveType?->name,
            'reason_id' => $this->adjustment_reason_id,
            'reason_name' => $this->adjustmentReason?->reason_name,
            'staff_id' => $this->hrms_staff_id,
            'staff_name' => $this->staff?->personal?->fullName,
            'staff_employee_number' => $this->staff?->employee_number,
            'staff_branch' => $this->staff?->employment?->branch?->code,
            'effective_date' => $this->effective_date,
            'no_of_days' => $this->days,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
