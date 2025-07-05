<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\StaffAccess;
use App\Models\SystemAccess;
use App\Models\StaffAuth;
use App\Models\Company;
use Modules\HRMS\Models\HRMSStaffPersonal;
use Modules\HRMS\Models\HRMSStaff;
use Modules\HRMS\Models\HRMSStaffEmployment;
use Modules\HRMS\Models\HRMSAppraisalType;
use Modules\HRMS\Models\HRMSLeaveRank;
use Modules\HRMS\Models\HRMSDesignation;
use Modules\HRMS\Models\HRMSPayGroup;
use Modules\HRMS\Models\HRMSLeaveType;
use Modules\HRMS\Models\HRMSLeaveModel;
use Modules\HRMS\Models\HRMSLeaveEntitlement;
use Modules\HRMS\Models\HRMSLeaveAdjustmentReason;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Added for DB transactions

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Create a Company
            $company = Company::firstOrCreate(
                ['name' => 'My Awesome Company'],
                ['is_active' => true]
            );

            // 2. Create a Branch linked to the Company
            $branch = Branch::firstOrCreate(
                ['company_id' => $company->id, 'name' => 'HQ Branch'],
                ['is_active' => true]
            );

            // 3. Create HRMS Lookup Data (Designation, Leave Rank, Pay Group, Appraisal Type)
            $designation = HRMSDesignation::firstOrCreate(
                ['name' => 'Software Engineer'],
                ['is_active' => true]
            );

            $leaveRank = HRMSLeaveRank::firstOrCreate(
                ['name' => 'Senior Staff'],
                ['is_active' => true]
            );

            $payGroup = HRMSPayGroup::firstOrCreate(
                ['name' => 'Monthly Paid'],
                ['is_active' => true]
            );

            $appraisalType = HRMSAppraisalType::firstOrCreate(
                ['name' => 'Annual Review'],
                ['is_active' => true]
            );

            // 4. Create SystemAccess records for the Branch
            $systemAccess = SystemAccess::firstOrCreate(
                ['access_name' => 'Admin Access', 'branch_id' => $branch->id],
                ['hrms' => true]
            );

            $systemAccess1 = SystemAccess::firstOrCreate(
                ['access_name' => 'Admin Access 2', 'branch_id' => $branch->id],
                ['hrms' => true]
            );

            // 5. Create a StaffAuth (login) record
            $staffAuth = StaffAuth::firstOrCreate(
                ['username' => 'admin_user'],
                ['password' => Hash::make('password123'), 'is_active' => true]
            );

            // 6. Create StaffAccess records, linking StaffAuth to SystemAccess
            StaffAccess::firstOrCreate(
                ['staff_auth_id' => $staffAuth->id, 'system_access_id' => $systemAccess->id]
            );
            StaffAccess::firstOrCreate(
                ['staff_auth_id' => $staffAuth->id, 'system_access_id' => $systemAccess1->id]
            );

            // 7. Create HRMSStaffPersonal details
            $staffPersonal = HRMSStaffPersonal::firstOrCreate(
                ['work_email' => 'john.doe@example.com'], // Using unique field for firstOrCreate
                [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'fullName' => 'John Doe',
                    'dob' => '1990-01-15',
                    'gender' => 'Male',
                    'marital_status' => 'Single',
                    'blood_group' => 'O+',
                    'religion' => 'Christianity',
                    'race' => 'Caucasian',
                    'mobile_no' => '0123456789',
                    'present_address' => '123 Main St',
                    'present_city' => 'Kota Kinabalu',
                    'present_state' => 'Sabah',
                ]
            );

            // 8. Create HRMSStaffEmployment details
            $staffEmployment = HRMSStaffEmployment::firstOrCreate(
                ['employee_number' => 'EMP001'], // Using unique field for firstOrCreate
                [
                    'branch_id' => $branch->id,
                    'hrms_designation_id' => $designation->id,
                    'hrms_leave_rank_id' => $leaveRank->id,
                    'hrms_pay_group_id' => $payGroup->id,
                    'hrms_appraisal_type_id' => $appraisalType->id,
                    'joining_date' => '2020-03-01', // Set a specific joining date for year_of_service calculation
                    'confirmation_date' => '2020-09-01',
                    'training_period' => 30,
                    'probation_period' => 180,
                    'notice_period' => 30,
                ]
            );

            // 9. Create the central HRMSStaff record
            $johnDoeStaff = HRMSStaff::firstOrCreate(
                ['staff_auth_id' => $staffAuth->id], // Using unique field for firstOrCreate
                [
                    'hrms_staff_personal_id' => $staffPersonal->id,
                    'hrms_staff_employment_id' => $staffEmployment->id,
                ]
            );

            // --- HRMS Leave System Seeding ---

            // Seed default Leave Types
            $sickLeaveType = HRMSLeaveType::firstOrCreate(
                ['name' => 'Sick Leave'],
                [
                    'default_no_of_days' => 14,
                    'status' => 'active',
                    'earned_rules' => 'N/A',
                    'need_blocking' => false,
                    'leave_model' => true, // This leave type will use a leave model for entitlements
                    'allow_carry_forward' => false,
                    'require_attachment' => true,
                    'apply_by_hours' => false,
                    'apply_within_days' => 7,
                    'background_color' => '#f0ad4e',
                    'remarks' => 'Leave for sickness, medical certificate required.',
                    'replacement_shift' => false,
                ]
            );

            $emergencyLeaveType = HRMSLeaveType::firstOrCreate(
                ['name' => 'Emergency Leave'],
                [
                    'default_no_of_days' => 3,
                    'status' => 'active',
                    'earned_rules' => 'N/A',
                    'need_blocking' => false,
                    'leave_model' => false, // This leave type does NOT use a leave model for entitlements
                    'allow_carry_forward' => false,
                    'require_attachment' => true,
                    'apply_by_hours' => false,
                    'apply_within_days' => 3,
                    'background_color' => '#d9534f',
                    'remarks' => 'For unforeseen urgent situations.',
                    'replacement_shift' => false,
                ]
            );

            $annualLeaveType = HRMSLeaveType::firstOrCreate(
                ['name' => 'Annual Leave'],
                [
                    'default_no_of_days' => 12,
                    'status' => 'active',
                    'earned_rules' => 'Accrued Monthly',
                    'need_blocking' => true,
                    'leave_model' => true, // This leave type will use a leave model for entitlements
                    'allow_carry_forward' => true,
                    'require_attachment' => false,
                    'apply_by_hours' => false,
                    'apply_within_days' => 2,
                    'background_color' => '#5cb85c',
                    'remarks' => 'Standard paid time off.',
                    'replacement_shift' => false,
                ]
            );

            $compassionateLeaveType = HRMSLeaveType::firstOrCreate(
                ['name' => 'Compassionate Leave'],
                [
                    'default_no_of_days' => 3,
                    'status' => 'active',
                    'earned_rules' => 'N/A',
                    'need_blocking' => false,
                    'leave_model' => false,
                    'allow_carry_forward' => false,
                    'require_attachment' => true,
                    'apply_by_hours' => false,
                    'apply_within_days' => 5,
                    'background_color' => '#5bc0de',
                    'remarks' => 'Granted for personal emergencies or bereavement.',
                    'replacement_shift' => false,
                ]
            );

            $maternityLeaveType = HRMSLeaveType::firstOrCreate(
                ['name' => 'Maternity Leave'],
                [
                    'default_no_of_days' => 90,
                    'status' => 'active',
                    'earned_rules' => 'Statutory',
                    'need_blocking' => true,
                    'leave_model' => true,
                    'allow_carry_forward' => false,
                    'require_attachment' => true,
                    'apply_by_hours' => false,
                    'apply_within_days' => 60,
                    'background_color' => '#6c757d',
                    'remarks' => 'Statutory maternity leave for female employees.',
                    'replacement_shift' => false,
                ]
            );

            // Seed some common Leave Adjustment Reasons
            HRMSLeaveAdjustmentReason::firstOrCreate(['reason_name' => 'Additional Entitlement'], ['is_active' => true, 'built_in' => true]);
            HRMSLeaveAdjustmentReason::firstOrCreate(['reason_name' => 'Deduction for Unpaid Leave'], ['is_active' => true, 'built_in' => true]);
            HRMSLeaveAdjustmentReason::firstOrCreate(['reason_name' => 'Carry Forward Expiry'], ['is_active' => true, 'built_in' => true]);
            HRMSLeaveAdjustmentReason::firstOrCreate(['reason_name' => 'Leave Buyback'], ['is_active' => true, 'built_in' => true]);
            HRMSLeaveAdjustmentReason::firstOrCreate(['reason_name' => 'Early Resignation Adjustment'], ['is_active' => true, 'built_in' => true]);

            // --- Seed HRMSLeaveModel rules (uncommented for testing backend logic) ---
            // These rules define how many days are *granted* based on Leave Type, Leave Rank, and Years of Service.
            if ($annualLeaveType && $leaveRank) { // Ensure both exist
                // Rule 1: Senior Staff with 0-2 years of service get 12 days annual leave
                HRMSLeaveModel::firstOrCreate(
                    [
                        'hrms_leave_type_id' => $annualLeaveType->id,
                        'hrms_leave_rank_id' => $leaveRank->id, // Senior Staff
                        'year_of_service' => 0, // For employees with 0 years of service
                    ],
                    [
                        'entitled_days' => 12,
                        'carry_forward_days' => 6,
                    ]
                );
                $this->command->info('Seeded HRMSLeaveModel rule: Annual Leave for Senior Staff (0 yrs) gets 12 days.');

                // Rule 2: Senior Staff with 3+ years of service get 15 days annual leave
                HRMSLeaveModel::firstOrCreate(
                    [
                        'hrms_leave_type_id' => $annualLeaveType->id,
                        'hrms_leave_rank_id' => $leaveRank->id,
                        'year_of_service' => 3, // For employees with 3+ years of service
                    ],
                    [
                        'entitled_days' => 15,
                        'carry_forward_days' => 7,
                    ]
                );
                $this->command->info('Seeded HRMSLeaveModel rule: Annual Leave for Senior Staff (3+ yrs) gets 15 days.');
            }

            if ($sickLeaveType && $leaveRank) {
                // Rule 1: Senior Staff gets 14 days sick leave, regardless of years of service
                HRMSLeaveModel::firstOrCreate(
                    [
                        'hrms_leave_type_id' => $sickLeaveType->id,
                        'hrms_leave_rank_id' => $leaveRank->id,
                        'year_of_service' => 0, // Applies to all years if no other rule for higher years
                    ],
                    [
                        'entitled_days' => 14,
                        'carry_forward_days' => 0,
                    ]
                );
                $this->command->info('Seeded HRMSLeaveModel rule: Sick Leave for Senior Staff gets 14 days.');
            }


            // --- Seed HRMSLeaveEntitlements for John Doe (now consulting HRMSLeaveModel) ---
            $currentYear = Carbon::now()->year;

            if ($johnDoeStaff && $johnDoeStaff->employment) {
                $joiningDate = Carbon::parse($johnDoeStaff->employment->joining_date);
                $yearsOfService = $joiningDate->diffInYears(Carbon::now());
                $staffLeaveRankId = $johnDoeStaff->employment->hrms_leave_rank_id;

                $leaveTypesToProcess = HRMSLeaveType::where('leave_model', true)->get();

                foreach ($leaveTypesToProcess as $leaveType) {
                    $entitledDays = 0;
                    $carryForwardDays = 0;

                    // Find the most appropriate HRMSLeaveModel rule based on years of service
                    $leaveModelRule = HRMSLeaveModel::where('hrms_leave_type_id', $leaveType->id)
                        ->where('hrms_leave_rank_id', $staffLeaveRankId)
                        ->where('year_of_service', '<=', $yearsOfService)
                        ->orderByDesc('year_of_service') // Get the rule for the highest matching year_of_service
                        ->first();

                    if ($leaveModelRule) {
                        $entitledDays = $leaveModelRule->entitled_days;
                        $carryForwardDays = $leaveModelRule->carry_forward_days; // Though not used for initial entitlement, it's part of the rule.
                        $this->command->info("Found HRMSLeaveModel rule for {$leaveType->name} (Rank: {$leaveRank->name}, YOS: {$yearsOfService}) with {$entitledDays} days.");
                    } else {
                        // Fallback: If no specific model rule found, use default_no_of_days from LeaveType
                        $entitledDays = $leaveType->default_no_of_days;
                        $this->command->warn("No specific HRMSLeaveModel rule found for {$leaveType->name} (Rank: {$leaveRank->name}, YOS: {$yearsOfService}). Using default_no_of_days from LeaveType: {$entitledDays} days.");
                    }

                    HRMSLeaveEntitlement::firstOrCreate(
                        [
                            'hrms_staff_id' => $johnDoeStaff->id,
                            'hrms_leave_type_id' => $leaveType->id,
                            'year' => $currentYear,
                        ],
                        [
                            'entitled_days' => $entitledDays,
                            'consumed_days' => 0,
                            'remaining_days' => $entitledDays,
                        ]
                    );
                    $this->command->info("Seeded {$leaveType->name} entitlement ({$entitledDays} days) for John Doe for {$currentYear}.");
                }
            } else {
                $this->command->warn('Could not find John Doe staff or employment details to seed leave entitlements. Ensure initial staff creation is successful.');
            }

            // If you still need to call other module seeders (e.g., IMSDatabaseSeeder), uncomment the line below:
            // $this->call(\Modules\IMS\Database\Seeders\IMSDatabaseSeeder::class);
        });
    }
}
// $this->call(\Modules\HRMS\Database\Seeders\HRMSDatabaseSeeder::class);
// $this->call(\Modules\IMS\Database\Seeders\IMSDatabaseSeeder::class);
