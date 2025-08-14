<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSStaffPersonal;
use Modules\HRMS\Models\HRMSStaffEmployment;
use Modules\HRMS\Models\HRMSStaff;
use Modules\HRMS\Transformers\HRMSStaffResource;
use App\Models\SystemAccess;
use App\Models\StaffAccess;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Transformers\simpleStaffListResource;


class HRMSStaffController extends Controller
{
    /**
     * Display a listing of the HRMS staff.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min($perPage, 100)); // Allow between 1 and 100

        $hrmsStaff = HRMSStaff::with([
            'auth.staffAccess.systemAccess',
            'personal.children',
            'employment.branch',
            'employment.designation',
            'employment.leaveRank',
            'employment.payGroup',
            'employment.appraisalType'
        ])->paginate($perPage);

        return HRMSStaffResource::collection($hrmsStaff);
    }

    public function show($id)
    {
        $staff = HRMSStaff::with([
            'auth.staffAccess.systemAccess',
            'personal.children',
            'employment.branch',
            'employment.designation',
            'employment.leaveRank',
            'employment.payGroup',
            'employment.appraisalType'
        ])->findOrFail($id);

        return new HRMSStaffResource($staff);
    }




    /**
     * Create a new staff record including authentication, personal, employment,
     * and dependent child details for the HRMS module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createStaff(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $validatedData = $request->validate([
                    // StaffAuth Validation
                    'auth.username' => 'nullable|string|max:255|unique:staff_auth,username',
                    'auth.password' => 'nullable|string|min:8',
                    'auth.is_active' => 'boolean',

                    // HRMSStaffPersonal Validation
                    'personal.firstName' => 'required|string|max:255',
                    'personal.middleName' => 'required|string|max:255',
                    'personal.lastName' => 'required|string|max:255',
                    'personal.fullName' => 'nullable|string|max:255',
                    'personal.dob' => 'nullable|date',
                    'personal.gender' => ['nullable', Rule::in(['Male', 'Female'])],
                    'personal.marital_status' => 'nullable|string|max:255',
                    'personal.blood_group' => 'nullable|string|max:255',
                    'personal.religion' => 'nullable|string|max:255',
                    'personal.race' => 'nullable|string|max:255',
                    'personal.image_url' => 'nullable|url|max:255',
                    'personal.bank_account_no' => 'nullable|string|max:255',
                    'personal.bank_name' => 'nullable|string|max:255',
                    'personal.bank_branch' => 'nullable|string|max:255',
                    'personal.socso_no' => 'nullable|string|max:255',
                    'personal.epf_no' => 'nullable|string|max:255',
                    'personal.income_tax_no' => 'nullable|string|max:255',
                    'personal.ic_no' => 'nullable|string|max:255|unique:hrms_staff_personal,ic_no',
                    'personal.old_ic_no' => 'nullable|string|max:255',
                    'personal.passport_no' => 'nullable|string|max:255|unique:hrms_staff_personal,passport_no',
                    'personal.driving_license_no' => 'nullable|string|max:255',
                    'personal.driving_license_category' => 'nullable|string|max:255',
                    'personal.driving_license_expiry_date' => 'nullable|string|max:255',
                    'personal.gdl_expiry_date' => 'nullable|string|max:255',
                    'personal.work_permit_expiry_date' => 'nullable|string|max:255',
                    'personal.father_name' => 'nullable|string|max:255',
                    'personal.father_dob' => 'nullable|date',
                    'personal.mother_name' => 'nullable|string|max:255',
                    'personal.mother_dob' => 'nullable|date',
                    'personal.spouse_name' => 'nullable|string|max:255',
                    'personal.spouse_dob' => 'nullable|date',
                    'personal.mobile_no' => 'nullable|string|max:20',
                    'personal.work_no' => 'nullable|string|max:20',
                    'personal.landline_no' => 'nullable|string|max:20',
                    'personal.work_email' => 'nullable|email|max:255|unique:hrms_staff_personal,work_email',
                    'personal.other_email' => 'nullable|email|max:255',
                    'personal.present_address' => 'nullable|string|max:255',
                    'personal.present_city' => 'nullable|string|max:255',
                    'personal.present_state' => 'nullable|string|max:255',
                    'personal.permanent_address' => 'nullable|string|max:255',
                    'personal.permanent_city' => 'nullable|string|max:255',
                    'personal.permanent_state' => 'nullable|string|max:255',
                    'personal.mailing_address' => 'nullable|string|max:255',
                    'personal.emergency_contact' => 'nullable|string|max:255',
                    'personal.emergency_relation' => 'nullable|string|max:255',
                    'personal.emergency_landline_no' => 'nullable|string|max:20',
                    'personal.emergency_work_no' => 'nullable|string|max:20',
                    'personal.emergency_mobile_no' => 'nullable|string|max:20',
                    'personal.emergency_address' => 'nullable|string|max:255',

                    // HRMSStaffEmployment Validation
                    'employment.branch_id' => 'required|exists:branch,id',
                    'employment.hrms_designation_id' => 'required|exists:hrms_designation,id',
                    'employment.hrms_leave_rank_id' => 'required|exists:hrms_leave_rank,id',
                    'employment.hrms_pay_group_id' => 'required|exists:hrms_pay_group,id',
                    'employment.hrms_appraisal_type_id' => 'required|exists:hrms_appraisal_type,id',
                    'employment.employee_number' => 'nullable|string|max:255|unique:hrms_staff_employment,employee_number',
                    'employment.joining_date' => 'required|date',
                    'employment.confirmation_date' => 'nullable|date|after_or_equal:employment.joining_date',
                    'employment.relieving_date' => 'nullable|date|after_or_equal:employment.joining_date',
                    'employment.training_period' => 'nullable|integer|min:0',
                    'employment.probation_period' => 'nullable|integer|min:0',
                    'employment.notice_period' => 'nullable|integer|min:0',

                    // SystemAccess linkage
                    // 'system_access_id' => 'nullable|array',
                    // 'system_access_id.*' => 'exists:system_access,id',


                    // Dependent Children Validation (New Section)
                    'children' => 'nullable|array', // 'children' can be an array or null
                    'children.*.name' => 'required_with:children|string|max:255', // Each child must have a name if 'children' is present
                    'children.*.dob' => 'nullable|date',
                    'children.*.remark' => 'nullable|string|max:255',
                ]);

                // Use a database transaction to ensure all related records are created or none are.
                return DB::transaction(function () use ($validatedData) {
                    // 2. Create StaffAuth record
                    $staffAuth = StaffAuth::create([
                        'username' => $validatedData['auth']['username'],
                        'password' => Hash::make($validatedData['auth']['password']),
                        'is_active' => $validatedData['auth']['is_active'] ?? true,
                    ]);

                    // 3. Create HRMSStaffPersonal record
                    $staffPersonal = HRMSStaffPersonal::create([
                        'firstName' => $validatedData['personal']['firstName'],
                        'middleName' => $validatedData['personal']['middleName'],
                        'lastName' => $validatedData['personal']['lastName'],
                        'fullName' => $validatedData['personal']['fullName'] ?? ($validatedData['personal']['firstName'] . ' ' . $validatedData['personal']['lastName']),
                        'dob' => $validatedData['personal']['dob'] ?? null,
                        'gender' => $validatedData['personal']['gender'] ?? 'Male',
                        'marital_status' => $validatedData['personal']['marital_status'] ?? null,
                        'blood_group' => $validatedData['personal']['blood_group'] ?? null,
                        'religion' => $validatedData['personal']['religion'] ?? null,
                        'race' => $validatedData['personal']['race'] ?? null,
                        'image_url' => $validatedData['personal']['image_url'] ?? null,
                        'bank_account_no' => $validatedData['personal']['bank_account_no'] ?? null,
                        'bank_name' => $validatedData['personal']['bank_name'] ?? null,
                        'bank_branch' => $validatedData['personal']['bank_branch'] ?? null,
                        'socso_no' => $validatedData['personal']['socso_no'] ?? null,
                        'epf_no' => $validatedData['personal']['epf_no'] ?? null,
                        'income_tax_no' => $validatedData['personal']['income_tax_no'] ?? null,
                        'ic_no' => $validatedData['personal']['ic_no'] ?? null,
                        'old_ic_no' => $validatedData['personal']['old_ic_no'] ?? null,
                        'passport_no' => $validatedData['personal']['passport_no'] ?? null,
                        'driving_license_no' => $validatedData['personal']['driving_license_no'] ?? null,
                        'driving_license_category' => $validatedData['personal']['driving_license_category'] ?? null,
                        'driving_license_expiry_date' => $validatedData['personal']['driving_license_expiry_date'] ?? null,
                        'gdl_expiry_date' => $validatedData['personal']['gdl_expiry_date'] ?? null,
                        'work_permit_expiry_date' => $validatedData['personal']['work_permit_expiry_date'] ?? null,
                        'father_name' => $validatedData['personal']['father_name'] ?? null,
                        'father_dob' => $validatedData['personal']['father_dob'] ?? null,
                        'mother_name' => $validatedData['personal']['mother_name'] ?? null,
                        'mother_dob' => $validatedData['personal']['mother_dob'] ?? null,
                        'spouse_name' => $validatedData['personal']['spouse_name'] ?? null,
                        'spouse_dob' => $validatedData['personal']['spouse_dob'] ?? null,
                        'mobile_no' => $validatedData['personal']['mobile_no'] ?? null,
                        'work_no' => $validatedData['personal']['work_no'] ?? null,
                        'landline_no' => $validatedData['personal']['landline_no'] ?? null,
                        'work_email' => $validatedData['personal']['work_email'] ?? null,
                        'other_email' => $validatedData['personal']['other_email'] ?? null,
                        'present_address' => $validatedData['personal']['present_address'] ?? null,
                        'present_city' => $validatedData['personal']['present_city'] ?? null,
                        'present_state' => $validatedData['personal']['present_state'] ?? null,
                        'permanent_address' => $validatedData['personal']['permanent_address'] ?? null,
                        'permanent_city' => $validatedData['personal']['permanent_city'] ?? null,
                        'permanent_state' => $validatedData['personal']['permanent_state'] ?? null,
                        'mailing_address' => $validatedData['personal']['mailing_address'] ?? null,
                        'emergency_contact' => $validatedData['personal']['emergency_contact'] ?? null,
                        'emergency_relation' => $validatedData['personal']['emergency_relation'] ?? null,
                        'emergency_landline_no' => $validatedData['personal']['emergency_landline_no'] ?? null,
                        'emergency_work_no' => $validatedData['personal']['emergency_work_no'] ?? null,
                        'emergency_mobile_no' => $validatedData['personal']['emergency_mobile_no'] ?? null,
                        'emergency_address' => $validatedData['personal']['emergency_address'] ?? null,
                    ]);

                    // 4. Create HRMSStaffEmployment record
                    $staffEmployment = HRMSStaffEmployment::create([
                        'branch_id' => $validatedData['employment']['branch_id'],
                        'hrms_designation_id' => $validatedData['employment']['hrms_designation_id'],
                        'hrms_leave_rank_id' => $validatedData['employment']['hrms_leave_rank_id'],
                        'hrms_pay_group_id' => $validatedData['employment']['hrms_pay_group_id'],
                        'hrms_appraisal_type_id' => $validatedData['employment']['hrms_appraisal_type_id'],
                        'employee_number' => $validatedData['employment']['employee_number'] ?? null,
                        'joining_date' => $validatedData['employment']['joining_date'],
                        'confirmation_date' => $validatedData['employment']['confirmation_date'] ?? null,
                        'relieving_date' => $validatedData['employment']['relieving_date'] ?? null,
                        'training_period' => $validatedData['employment']['training_period'] ?? 0,
                        'probation_period' => $validatedData['employment']['probation_period'] ?? 0,
                        'notice_period' => $validatedData['employment']['notice_period'] ?? 0,
                    ]);

                    // 5. Create the central HRMSStaff record linking the above
                    $hrmsStaff = HRMSStaff::create([
                        'staff_auth_id' => $staffAuth->id,
                        'hrms_staff_personal_id' => $staffPersonal->id,
                        'hrms_staff_employment_id' => $staffEmployment->id,
                    ]);

                    // foreach ($validatedData['system_access_id'] as $accessId) {
                    //     StaffAccess::create([
                    //         'staff_auth_id' => $staffAuth->id,
                    //         'system_access_id' => $accessId,
                    //     ]);
                    // }

                    // 7. Create HRMSStaffDependentChild records (NEW LOGIC)
                    if (isset($validatedData['children']) && is_array($validatedData['children'])) {
                        foreach ($validatedData['children'] as $childData) {
                            $staffPersonal->children()->create([ // Use the relationship to create children
                                'name' => $childData['name'],
                                'dob' => $childData['dob'] ?? null,
                                'remark' => $childData['remark'] ?? null,
                            ]);
                        }
                    }

                    // 8. Return a success response with the newly created staff's authentication details
                    // Eager load relationships for the HRMSStaffResource
                    $hrmsStaff->load([
                        'auth.staffAccess.systemAccess',
                        'personal.children',
                        'employment.branch',
                        'employment.designation',
                        'employment.leaveRank',
                        'employment.payGroup',
                        'employment.appraisalType'
                    ]);

                    return response()->json([
                        'message' => 'Staff record created successfully.',
                        'staff' => new HRMSStaffResource($hrmsStaff), // Use the HRMSStaffResource here
                    ], 201); // 201 Created status code
                });
            });
        } catch (\Throwable $e) {
            Log::error('createStaff error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing staff record including authentication, personal, employment,
     * and dependent child details for the HRMS module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id The ID of the HRMSStaff record to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStaff(Request $request, $id)
    {
        try {
            // Start a database transaction to ensure atomicity
            return DB::transaction(function () use ($request, $id) {
                // 1. Find the HRMSStaff record
                $hrmsStaff = HRMSStaff::with([
                    'auth',
                    'personal',
                    'employment'
                ])->find($id);

                if (!$hrmsStaff) {
                    return response()->json(['message' => 'Staff record not found.'], 404);
                }

                // Get related model IDs for unique validation exclusions
                $staffAuthId = $hrmsStaff->staff_auth_id;
                $staffPersonalId = $hrmsStaff->hrms_staff_personal_id;
                $staffEmploymentId = $hrmsStaff->hrms_staff_employment_id;

                // 2. Validate the incoming request data
                $validatedData = $request->validate([
                    // StaffAuth Validation (all 'sometimes' as not all fields might be updated)
                    'auth.username' => [
                        'sometimes',
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('staff_auth', 'username')->ignore($staffAuthId),
                    ],
                    'auth.password' => 'sometimes|required|string|min:8',
                    'auth.is_active' => 'sometimes|boolean',

                    // HRMSStaffPersonal Validation
                    'personal.firstName' => 'sometimes|required|string|max:255',
                    'personal.middleName' => 'sometimes|required|string|max:255',
                    'personal.lastName' => 'sometimes|required|string|max:255',
                    'personal.fullName' => 'nullable|string|max:255', // Nullable means it can be explicitly set to null
                    'personal.dob' => 'nullable|date',
                    'personal.gender' => ['nullable', Rule::in(['Male', 'Female'])],
                    'personal.marital_status' => 'nullable|string|max:255',
                    'personal.blood_group' => 'nullable|string|max:255',
                    'personal.religion' => 'nullable|string|max:255',
                    'personal.race' => 'nullable|string|max:255',
                    'personal.image_url' => 'nullable|url|max:255',
                    'personal.bank_account_no' => 'nullable|string|max:255',
                    'personal.bank_name' => 'nullable|string|max:255',
                    'personal.bank_branch' => 'nullable|string|max:255',
                    'personal.socso_no' => 'nullable|string|max:255',
                    'personal.epf_no' => 'nullable|string|max:255',
                    'personal.income_tax_no' => 'nullable|string|max:255',
                    'personal.ic_no' => [
                        'nullable',
                        'string',
                        'max:255',
                        Rule::unique('hrms_staff_personal', 'ic_no')->ignore($staffPersonalId),
                    ],
                    'personal.old_ic_no' => 'nullable|string|max:255',
                    'personal.passport_no' => [
                        'nullable',
                        'string',
                        'max:255',
                        Rule::unique('hrms_staff_personal', 'passport_no')->ignore($staffPersonalId),
                    ],
                    'personal.driving_license_no' => 'nullable|string|max:255',
                    'personal.driving_license_category' => 'nullable|string|max:255',
                    'personal.driving_license_expiry_date' => 'nullable|string|max:255',
                    'personal.gdl_expiry_date' => 'nullable|string|max:255',
                    'personal.work_permit_expiry_date' => 'nullable|string|max:255',
                    'personal.father_name' => 'nullable|string|max:255',
                    'personal.father_dob' => 'nullable|date',
                    'personal.mother_name' => 'nullable|string|max:255',
                    'personal.mother_dob' => 'nullable|date',
                    'personal.spouse_name' => 'nullable|string|max:255',
                    'personal.spouse_dob' => 'nullable|date',
                    'personal.mobile_no' => 'nullable|string|max:20',
                    'personal.work_no' => 'nullable|string|max:20',
                    'personal.landline_no' => 'nullable|string|max:20',
                    'personal.work_email' => [
                        'nullable',
                        'email',
                        'max:255',
                        Rule::unique('hrms_staff_personal', 'work_email')->ignore($staffPersonalId),
                    ],
                    'personal.other_email' => 'nullable|email|max:255',
                    'personal.present_address' => 'nullable|string|max:255',
                    'personal.present_city' => 'nullable|string|max:255',
                    'personal.present_state' => 'nullable|string|max:255',
                    'personal.permanent_address' => 'nullable|string|max:255',
                    'personal.permanent_city' => 'nullable|string|max:255',
                    'personal.permanent_state' => 'nullable|string|max:255',
                    'personal.mailing_address' => 'nullable|string|max:255',
                    'personal.emergency_contact' => 'nullable|string|max:255',
                    'personal.emergency_relation' => 'nullable|string|max:255',
                    'personal.emergency_landline_no' => 'nullable|string|max:20',
                    'personal.emergency_work_no' => 'nullable|string|max:20',
                    'personal.emergency_mobile_no' => 'nullable|string|max:20',
                    'personal.emergency_address' => 'nullable|string|max:255',

                    // HRMSStaffEmployment Validation
                    'employment.branch_id' => 'sometimes|required|exists:branch,id',
                    'employment.hrms_designation_id' => 'sometimes|required|exists:hrms_designation,id',
                    'employment.hrms_leave_rank_id' => 'sometimes|required|exists:hrms_leave_rank,id',
                    'employment.hrms_pay_group_id' => 'sometimes|required|exists:hrms_pay_group,id',
                    'employment.hrms_appraisal_type_id' => 'sometimes|required|exists:hrms_appraisal_type,id',
                    'employment.employee_number' => [
                        'nullable',
                        'string',
                        'max:255',
                        Rule::unique('hrms_staff_employment', 'employee_number')->ignore($staffEmploymentId),
                    ],
                    'employment.joining_date' => 'sometimes|required|date',
                    'employment.confirmation_date' => 'nullable|date|after_or_equal:employment.joining_date',
                    'employment.relieving_date' => 'nullable|date|after_or_equal:employment.joining_date',
                    'employment.training_period' => 'nullable|integer|min:0',
                    'employment.probation_period' => 'nullable|integer|min:0',
                    'employment.notice_period' => 'nullable|integer|min:0',

                    // SystemAccess linkage
                    'system_access_id' => 'sometimes|required|array',
                    'system_access_id.*' => 'exists:system_access,id',

                    // Dependent Children Validation
                    'children' => 'nullable|array',
                    'children.*.name' => 'required_with:children|string|max:255',
                    'children.*.dob' => 'nullable|date',
                    'children.*.remark' => 'nullable|string|max:255',
                ]);

                // 3. Update StaffAuth record
                if (isset($validatedData['auth'])) {
                    $authData = $validatedData['auth'];
                    if (isset($authData['password'])) {
                        $authData['password'] = Hash::make($authData['password']);
                    }
                    $hrmsStaff->auth->update($authData);
                }

                // 4. Update HRMSStaffPersonal record
                if (isset($validatedData['personal'])) {
                    $personalData = $validatedData['personal'];
                    // Auto-generate fullName if firstName or lastName are updated and fullName is not provided
                    if ((isset($personalData['firstName']) || isset($personalData['lastName'])) && !isset($personalData['fullName'])) {
                        $personalData['fullName'] = ($personalData['firstName'] ?? $hrmsStaff->personal->firstName) . ' ' . ($personalData['lastName'] ?? $hrmsStaff->personal->lastName);
                    }
                    $hrmsStaff->personal->update($personalData);
                }

                // 5. Update HRMSStaffEmployment record
                if (isset($validatedData['employment'])) {
                    $hrmsStaff->employment->update($validatedData['employment']);
                }

                // 6. Update StaffAccess linkage (delete all existing and re-create)
                if (isset($validatedData['system_access_id'])) {
                    $hrmsStaff->auth->staffAccess()->delete(); // Delete all existing access records
                    foreach ($validatedData['system_access_id'] as $accessId) {
                        StaffAccess::create([
                            'staff_auth_id' => $hrmsStaff->auth->id,
                            'system_access_id' => $accessId,
                        ]);
                    }
                }

                // 7. Update HRMSStaffDependentChild records (delete all existing and re-create)
                if (isset($validatedData['children'])) {
                    $hrmsStaff->personal->children()->delete(); // Delete all existing dependent children
                    foreach ($validatedData['children'] as $childData) {
                        $hrmsStaff->personal->children()->create([
                            'name' => $childData['name'],
                            'dob' => $childData['dob'] ?? null,
                            'remark' => $childData['remark'] ?? null,
                        ]);
                    }
                }

                // 8. Reload relationships for the HRMSStaffResource to ensure fresh data
                $hrmsStaff->load([
                    'auth.staffAccess.systemAccess',
                    'personal.children',
                    'employment.branch',
                    'employment.designation',
                    'employment.leaveRank',
                    'employment.payGroup',
                    'employment.appraisalType'
                ]);

                // 9. Return a success response with the updated staff's details
                return response()->json([
                    'message' => 'Staff record updated successfully.',
                    'staff' => new HRMSStaffResource($hrmsStaff), // Use the HRMSStaffResource here
                ], 200); // 200 OK status code for successful update
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity status code
        } catch (\Throwable $e) {
            // Catch any other exceptions and log them
            Log::error('updateStaff error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(), // Log request data for debugging
                'staff_id' => $id,
            ]);
            return response()->json([
                'message' => 'An error occurred while updating the staff record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function simpleStaffList()
    {
        $staffList = HRMSStaff::with(['personal', 'employment']);

        return simpleStaffListResource::collection($staffList->get());
    }
}
