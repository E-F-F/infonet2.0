<?php

namespace Modules\HRMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HRMS\Models\HRMSAppraisalType;
use Modules\HRMS\Models\HRMSEvent;
use Modules\HRMS\Models\HRMSEventType;
use Modules\HRMS\Models\HRMSLeaveRank;
use Modules\HRMS\Models\HRMSLeaveType;
use Modules\HRMS\Models\HRMSPayGroup;
use Modules\HRMS\Models\HRMSStaff;
use Modules\HRMS\Models\HRMSStaffEmployment;
use Modules\HRMS\Models\HRMSStaffPersonal;
use Modules\HRMS\Models\HRMSTrainingAwardType;
use Modules\HRMS\Models\HRMSTrainingType;

class HRMSDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HRMSLeaveRank::create([
            'name' => 'A',
            'is_active' => true,
        ]);
        HRMSLeaveRank::create([
            'name' => 'B',
            'is_active' => true,
        ]);
        HRMSLeaveRank::create([
            'name' => 'C',
            'is_active' => true,
        ]);
        HRMSLeaveRank::create([
            'name' => 'NIL',
            'is_active' => true,
        ]);
        HRMSPayGroup::create([
            'name' => 'General',
            'is_active' => true,
        ]);
        HRMSAppraisalType::create([
            'name' => 'No Appraisal',
            'is_active' => true,
        ]);
        HRMSAppraisalType::create([
            'name' => 'Monthly',
            'is_active' => true,
        ]);
        HRMSAppraisalType::create([
            'name' => 'Bi-Monthly',
            'is_active' => true,
        ]);
        HRMSAppraisalType::create([
            'name' => 'Quarterly',
            'is_active' => true,
        ]);
        HRMSEventType::create([
            'name' => 'Outstation Service and Repairing',
            'is_active' => true,
        ]);
        HRMSEventType::create([
            'name' => 'Road Show',
            'is_active' => true,
        ]);
        HRMSEventType::create([
            'name' => 'Spot Sales',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'CV IDS & Basic Warranty Train',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'CV Product Training',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'CV Product & Waranty Training',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'CV Sales & Marketing Training',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'LCV 4x4 Off Road Experiance',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'LCV Product Training',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'LCV Sales & Marketing Training',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'MHD Product & Selling Skill',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'MUX Product Training',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'Seminar',
            'is_active' => true,
        ]);
        HRMSTrainingType::create([
            'name' => 'Course',
            'is_active' => true,
        ]);
        HRMSTrainingAwardType::create([
            'name' => 'Fail',
            'is_active' => true,
        ]);
        HRMSTrainingAwardType::create([
            'name' => 'Certified',
            'is_active' => true,
        ]);
        HRMSTrainingAwardType::create([
            'name' => 'Attended',
            'is_active' => true,
        ]);
        HRMSTrainingAwardType::create([
            'name' => 'Pass',
            'is_active' => true,
        ]);
        HRMSLeaveType::create([
            'name' => 'Annual Leave',
            'is_active' => true,
        ]);
        HRMSLeaveType::create([
            'name' => 'Unpaid Leave',
            'is_active' => true,
        ]);
        HRMSLeaveType::create([
            'name' => 'Sick Leave',
            'is_active' => true,
        ]);
        HRMSLeaveType::create([
            'name' => 'Special Leave',
            'is_active' => true,
        ]);
        HRMSStaffPersonal::create([
            'firstName' => 'MOHAMAD IZWAN',
            'middleName' => null,
            'lastName' => 'BIN MANDA',
            'fullName' => 'MOHAMAD IZWAN BIN MANDA',
            'ic_no' => '012345-67-8901',
            'dob' => now(),
            'marital_status' => 'SINGLE',
            'nationality' => 'MALAYSIAN',
            'religion' => 'ISLAM',
            'race' => 'SABAHAN',
            'blood_group' => 'B-',
            'work_email' => 'mohdizwanmanda@gmail.com',
            'phone_number' => '011-253-70146',
            'home_address' => 'addresses',
            'image_url' => 'imageses',
        ]);
        HRMSStaffEmployment::create([
            'branch_id' => 1,
            // 'hrms_designation_id' => ,
            'hrms_leave_rank_id' => 1,
            'hrms_pay_group_id' => 1,
            'hrms_appraisal_type_id' => 1,
            'employee_number' => 'STAFF1',
            'joining_date' => now(),
        ]);
        HRMSStaff::create([
            'staff_auth_id' => 1,
            'hrms_staff_personal_id' => 1,
            'hrms_staff_employment_id' => 1,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 1,
            'title' => 'STAFF1',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'TWUHQ',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 2,
            'title' => 'EVENT1',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'KKHQ',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 3,
            'title' => 'EVENT2',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'UMKK1',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 3,
            'title' => 'EVENT2',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'UMKK1',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 1,
            'title' => 'EVENT2',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'UMKK1',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 2,
            'title' => 'EVENT2',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'UMKK1',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
        HRMSEvent::create([
            'hrms_event_type_id' => 3,
            'title' => 'EVENT2',
            'start_date' => now(),
            'end_date' => now(),
            'event_company' => 'UM',
            'event_branch' => 'UMKK1',
            'event_venue' => 'Padang',
            'remarks' => 'event something',
            // 'activity_logs' => 'write something',
            'is_active' => true,
        ]);
    }
}
