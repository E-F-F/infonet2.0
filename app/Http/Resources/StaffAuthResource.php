<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffAuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'is_active' => $this->is_active,

            'system_access_permissions' => $this->relationLoaded('staffAccess')
                ? $this->staffAccess->map(function ($staffAccess) {
                    $system = $staffAccess->relationLoaded('systemAccess') ? $staffAccess->systemAccess : null;

                    return [
                        'system_access_id' => $staffAccess->system_access_id,
                        'access_name' => $system?->access_name,
                        'branch_id' => $system?->branch_id,
                        'hrms' => $system?->hrms,
                        'crms' => $system?->crms,
                        'ims' => $system?->ims,
                        // Add others like 'ims', 'fms', etc.
                    ];
                })
                : [],

        ];
    }
}
