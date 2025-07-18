<?php

namespace Modules\HRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HRMSDesignationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,

            'department_id' => $this->hrms_department_id,
            'department_name' => $this->department?->name,

            'parent_designation_id' => $this->parent_designation_id,
            'parent_designation_name' => $this->parentDesignation?->name,

            'leave_rank_id' => $this->hrms_leave_rank_id,
            'leave_rank_name' => $this->leaveRank?->name,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
