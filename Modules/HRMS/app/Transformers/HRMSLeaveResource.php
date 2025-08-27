<?php

namespace Modules\HRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HRMSLeaveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'staff_id' => $this->hrms_staff_id,
            'staff_name' => $this->staff?->personal?->fullName,
            'staff_number' => $this->staff?->employee_number,
            'branch_id' => $this->branch_id,
            'branch_code' => $this->branch?->code,
            'leave_type_id' => $this->hrms_leave_type_id,
            'leave_type_name' => $this->leaveType?->name,
            'date_from' => $this->date_from,
            'session_from' => $this->session_from,
            'date_to' => $this->date_to,
            'session_to' => $this->session_to,
            'leave_purpose' => $this->leave_purpose,
            'status' => $this->status,
            'attachment_url' => $this->attachment_url,
            'remarks' => $this->remarks,
            'created_by' => $this->created_by,
            'created_by_name' => $this->creator?->personal?->fullName,
            'updated_by' => $this->updated_by,
            'updated_by_name' => $this->updater?->personal?->fullName,
            'approved_by' => $this->approved_by,
            'approved_by_name' => $this->approver ? $this->approver->personal?->fullName : null,
            'rejected_by' => $this->rejected_by,
            'rejected_by_name' => $this->rejector ? $this->rejector->personal?->fullName : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'approved_at' => $this->approved_at,
            'rejected_at' => $this->rejected_at,
            'deleted_at' => $this->deleted_at,

        ];
    }
}
