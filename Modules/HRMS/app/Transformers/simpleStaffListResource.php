<?php

namespace Modules\HRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class simpleStaffListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'name' => $this->personal?->fullName,
            'branch' => $this->employment?->branch?->code,
            'employee_number' => $this->employee_number,
        ];
    }
}
