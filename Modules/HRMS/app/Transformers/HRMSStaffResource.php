<?php

namespace Modules\HRMS\Transformers;

use App\Http\Resources\StaffAuthResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HRMSStaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff_auth_id' => $this->staff_auth_id,
            'hrms_staff_personal_id' => $this->hrms_staff_personal_id,
            'hrms_staff_employment_id' => $this->hrms_staff_employment_id,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),

            'auth_details' => $this->whenLoaded('auth', fn() => new StaffAuthResource($this->auth)),

            'personal_details' => $this->whenLoaded('personal', function () {
                return [
                    'firstName' => $this->personal->firstName,
                    'middleName' => $this->personal->middleName,
                    'lastName' => $this->personal->lastName,
                    'fullName' => $this->personal->fullName,
                    'dob' => optional($this->personal->dob)->toDateString(),
                    'gender' => $this->personal->gender,
                    'marital_status' => $this->personal->marital_status,
                    'blood_group' => $this->personal->blood_group,
                    'religion' => $this->personal->religion,
                    'race' => $this->personal->race,
                    'image_url' => $this->personal->image_url,
                    'bank_account_no' => $this->personal->bank_account_no,
                    'bank_name' => $this->personal->bank_name,
                    'bank_branch' => $this->personal->bank_branch,
                    'socso_no' => $this->personal->socso_no,
                    'epf_no' => $this->personal->epf_no,
                    'income_tax_no' => $this->personal->income_tax_no,
                    'ic_no' => $this->personal->ic_no,
                    'old_ic_no' => $this->personal->old_ic_no,
                    'passport_no' => $this->personal->passport_no,
                    'driving_license_no' => $this->personal->driving_license_no,
                    'driving_license_category' => $this->personal->driving_license_category,
                    'driving_license_expiry_date' => $this->personal->driving_license_expiry_date,
                    'gdl_expiry_date' => $this->personal->gdl_expiry_date,
                    'work_permit_expiry_date' => $this->personal->work_permit_expiry_date,
                    'father_name' => $this->personal->father_name,
                    'father_dob' => optional($this->personal->father_dob)->toDateString(),
                    'mother_name' => $this->personal->mother_name,
                    'mother_dob' => optional($this->personal->mother_dob)->toDateString(),
                    'spouse_name' => $this->personal->spouse_name,
                    'spouse_dob' => optional($this->personal->spouse_dob)->toDateString(),
                    'mobile_no' => $this->personal->mobile_no,
                    'work_no' => $this->personal->work_no,
                    'landline_no' => $this->personal->landline_no,
                    'work_email' => $this->personal->work_email,
                    'other_email' => $this->personal->other_email,
                    'present_address' => $this->personal->present_address,
                    'present_city' => $this->personal->present_city,
                    'present_state' => $this->personal->present_state,
                    'permanent_address' => $this->personal->permanent_address,
                    'permanent_city' => $this->personal->permanent_city,
                    'permanent_state' => $this->personal->permanent_state,
                    'mailing_address' => $this->personal->mailing_address,
                    'emergency_contact' => $this->personal->emergency_contact,
                    'emergency_relation' => $this->personal->emergency_relation,
                    'emergency_landline_no' => $this->personal->emergency_landline_no,
                    'emergency_work_no' => $this->personal->emergency_work_no,
                    'emergency_mobile_no' => $this->personal->emergency_mobile_no,
                    'emergency_address' => $this->personal->emergency_address,

                    'children' => $this->personal->relationLoaded('children')
                        ? $this->personal->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'name' => $child->name,
                                'dob' => optional($child->dob)->toDateString(),
                                'remark' => $child->remark,
                            ];
                        })
                        : [],
                ];
            }),

            'employment_details' => $this->whenLoaded('employment', function () {
                return [
                    'branch_id' => $this->employment->branch_id,
                    'hrms_designation_id' => $this->employment->hrms_designation_id,
                    'department' => $this->employment->designation->department->name ?? null,
                    'hrms_leave_rank_id' => $this->employment->hrms_leave_rank_id,
                    'hrms_pay_group_id' => $this->employment->hrms_pay_group_id,
                    'hrms_appraisal_type_id' => $this->employment->hrms_appraisal_type_id,
                    'employee_number' => $this->employment->employee_number,
                    'joining_date' => optional($this->employment->joining_date)->toDateString(),
                    'confirmation_date' => optional($this->employment->confirmation_date)->toDateString(),
                    'relieving_date' => optional($this->employment->relieving_date)->toDateString(),
                    'training_period' => $this->employment->training_period,
                    'probation_period' => $this->employment->probation_period,
                    'notice_period' => $this->employment->notice_period,

                    'branch_name' => $this->employment->relationLoaded('branch')
                        ? optional($this->employment->branch)->name
                        : null,

                    'designation_name' => $this->employment->relationLoaded('designation')
                        ? optional($this->employment->designation)->name
                        : null,

                    'leave_rank_name' => $this->employment->relationLoaded('leaveRank')
                        ? optional($this->employment->leaveRank)->name
                        : null,

                    'pay_group_name' => $this->employment->relationLoaded('payGroup')
                        ? optional($this->employment->payGroup)->name
                        : null,

                    'appraisal_type_name' => $this->employment->relationLoaded('appraisalType')
                        ? optional($this->employment->appraisalType)->name
                        : null,
                ];
            }),
        ];
    }
}
