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
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class HRMSStaffController extends Controller
{

    public function index()
    {
        $staff = HRMSStaff::select('id', 'staff_auth_id', 'hrms_staff_personal_id', 'hrms_staff_employment_id')->with([
            'personal' => function ($query) {
                // Select specific columns from the 'personal' relationship
                // ALWAYS include the foreign key ('staff_id' or whatever links personal to staff)
                $query->select(
                    'id',
                    'fullName',
                    'dob',
                );
            },
            'employment' => function ($query) {
                // Select specific columns from the 'employment' relationship
                // ALWAYS include the foreign key ('staff_id' or whatever links employment to staff)
                $query->select('id', 'branch_id', 'hrms_designation_id', 'hrms_leave_rank_id', 'hrms_pay_group_id', 'hrms_appraisal_type_id', 'employee_number', 'joining_date', 'created_at');
            },
        ])->orderBy('id')->get();

        $rows = $staff->map(function ($s) {
            return [
                'id' => $s->id,
                'fullName' => $s->personal->fullName ?? '-',
                // 'ic_no' => $s->personal->ic_no ?? '-',
                'dob' => $s->personal->dob ?? '-',
                'designation' => $s->employment->designation->name ?? '-',
                'leaveRank' => $s->employment->leaveRank->name ?? '-',
                'payGroup' => $s->employment->payGroup->name ?? '-',
                'appraisalType' => $s->employment->appraisalType->name ?? '-',
                'employeeNumber' => $s->employment->employee_number ?? '-',
                'joiningDate' => $s->employment->joining_date
                    ? Carbon::parse($s->employment->joining_date)->format('Y-m-d')
                    : '-',
                'created_at' => $s->employment->created_at
                    ? Carbon::parse($s->employment->created_at)->format('Y-m-d H:i:s')
                    : '-',
                
            ];
        })->toArray();

        $headers = [
            'fullName' => 'Full Name',
            'employeeNumber' => 'Emp. No',
            'dob' => 'Birth Date',
            'designation' => 'Designation',
            'leaveRank' => 'Leave Rank',
            'payGroup' => 'Pay Group',
            'appraisalType' => 'Appraisal Type',
            'joiningDate' => 'Joining Date',
            'relievingDate' => 'Relieving Date',
            'created_at' => 'Created At',
        ];

        // $filters = [
        //     [
        //         'label' => 'Gender',
        //         'name' => 'gender',
        //         'options' => ['All', 'Male', 'Female'],
        //     ],
        //     [
        //         'label' => 'Marital Status',
        //         'name' => 'marital_status',
        //         'options' => ['All', 'Single', 'Married', 'Divorced'],
        //     ],
        // ];
        return view('hrms::staff_management.staffs.index', compact('headers', 'rows'));
    }

    public function show($id)
    {
        $staff = HRMSStaff::select(
            'staff_auth_id',          // Needed for 'auth' relationship
            'hrms_staff_personal_id', // Needed for 'personal' relationship
            'hrms_staff_employment_id' // Needed for 'employment' relationship
        )
            ->with([
                'personal',
                'employment',
                'employment.branch' => function ($query) {
                    $query->select('id', 'name'); // Assuming 'name' is the column for the branch name
                },
                'employment.designation' => function ($query) {
                    $query->select('id', 'name'); // Assuming 'name' is the column for the designation name
                },
                'employment.leaveRank' => function ($query) {
                    $query->select('id', 'name'); // Assuming 'name' is the column for the leave rank name
                },
                'employment.payGroup' => function ($query) {
                    $query->select('id', 'name'); // Assuming 'name' is the column for the pay group name
                },
            ])
            ->find($id);

        if (!$staff) {
            abort(404, 'Staff member not found.');
        }
        // return view('hrms::index', compact('staff'));
        return view('hrms::staff_management.staffs.profile', compact('staff'));
    }

    public function create()
    {
        return view('hrms::staff_management.staffs.create');
    }

    public function store(Request $request)
    {
        $authRules = [
            'username'  =>  'required|string|max:255',
            'password'  =>  'required|string|min:8|max:255',
        ];

        $personalRules = [
            'firstName'      => 'required|string|max:255',
            'middleName'     => 'nullable|string|max:255',
            'lastName'       => 'required|string|max:255',
            'fullName'       => 'required|string|max:255',
            'ic_no'          => 'required|string|max:20|unique:hrms_staff_personal,ic_no',
            'dob'            => 'required|date',
            'gender'         => 'required|in:Male,Female,Other',
            'marital_status' => 'required|string|max:50',
            'nationality'    => 'required|string|max:100',
            'religion'       => 'nullable|string|max:100',
            'race'           => 'nullable|string|max:100',
            'blood_group'    => 'nullable|string|max:10',
            'home_address'   => 'required|string|max:500',
            'image_url'      => 'nullable|url|max:2048',
        ];

        $employmentRules = [
            'branch_id'             => 'required|exists:branch,id',
            'hrms_designation_id'   => 'required|exists:hrms_designation,id',
            'hrms_leave_rank_id'    => 'required|exists:hrms_leave_rank,id',
            'hrms_pay_group_id'     => 'required|exists:hrms_pay_group,id',
            'hrms_appraisal_type_id' => 'required|exists:hrms_appraisal_type,id',
            'employee_number'       => 'required|string|max:50|unique:hrms_staff_employment,employee_number',
            'joining_date'          => 'required|date',
        ];

        // Combine all rules for validation
        $rules = array_merge($personalRules, $employmentRules, $authRules);

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), $rules);
            $validator->validate(); // This will throw a ValidationException on failure

        } catch (ValidationException $e) {
            // If validation fails, redirect back with input and errors
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // 2. Start a database transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // Extract personal data
            $personalData = $request->only(array_keys($personalRules));

            // 3. Create the personal staff record
            $staffPersonal = HRMSStaffPersonal::create($personalData);

            // Extract employment data
            $employmentData = $request->only(array_keys($employmentRules));

            // 4. Create the employment staff record
            $staffEmployment = HRMSStaffEmployment::create($employmentData);

            // 5. Create the main staff record, linking personal and employment IDs
            // For 'staff_auth_id', you might generate a UUID, or link to an existing user authentication ID.
            $staffAuthData = $request->only(array_keys($authRules)); // Adjust this based on how you handle authentication.

            $staffAuth = StaffAuth::create($staffAuthData);

            $staff = HRMSStaff::create([
                'staff_auth_id'            => $staffAuth->id,
                'hrms_staff_personal_id'   => $staffPersonal->id,
                'hrms_staff_employment_id' => $staffEmployment->id,
            ]);

            // If all operations are successful, commit the transaction
            DB::commit();

            // Redirect back to the form with a success message
            return redirect()->route('hrms.staff.create')->with('success', 'Staff member added successfully!');
            // Or redirect to a staff listing page:
            // return redirect()->route('hrms.staff.index')->with('success', 'Staff member added successfully!');

        } catch (\Exception $e) {
            // If any other error occurs, rollback the transaction
            DB::rollBack();

            // Redirect back to the form with an error message and previous input
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add staff member: ' . $e->getMessage()); // Keep $e->getMessage() for debugging
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('hrms::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
// Guna Nanti
// public function show($id)
//     {
//         $staff = HRMSStaff::select(
//             'staff_auth_id',          // Needed for 'auth' relationship
//             'hrms_staff_personal_id', // Needed for 'personal' relationship
//             'hrms_staff_employment_id' // Needed for 'employment' relationship
//         )
//             ->with([
//                 'personal' => function ($query) {
//                     // Select specific columns from the 'personal' relationship
//                     // You must include the foreign key that links 'personal' to 'HRMSStaff'
//                     // Assuming it's 'id' from HRMSStaff::hrms_staff_personal_id
//                     $query->select('id', 'fullName', 'gender', 'image_url');
//                 },
//                 'employment' => function ($query) {
//                     // Select specific columns from the 'employment' relationship
//                     // Assuming it's 'id' from HRMSStaff::hrms_staff_employment_id
//                     $query->select('id', 'branch_id', 'hrms_designation_id', 'hrms_leave_rank_id', 'hrms_pay_group_id');
//                 },
//                 // Eager load the new relationships nested under 'employment'
//                 'employment.branch' => function ($query) {
//                     $query->select('id', 'name'); // Assuming 'name' is the column for the branch name
//                 },
//                 'employment.designation' => function ($query) {
//                     $query->select('id', 'name'); // Assuming 'name' is the column for the designation name
//                 },
//                 'employment.leaveRank' => function ($query) {
//                     $query->select('id', 'name'); // Assuming 'name' is the column for the leave rank name
//                 },
//                 'employment.payGroup' => function ($query) {
//                     $query->select('id', 'name'); // Assuming 'name' is the column for the pay group name
//                 },
//                 'auth' => function ($query) {
//                     // Select columns for the 'auth' model itself.
//                     // You must include the foreign key that links 'auth' to 'HRMSStaff'
//                     // Assuming it's 'id' from HRMSStaff::staff_auth_id
//                     $query->select('id'); // Or any other columns from the 'auth' model needed by 'staffAccess'
//                 },
//                 'auth.staffAccess' => function ($query) {
//                     // Select columns for the 'staffAccess' model.
//                     // You must include the foreign key that links 'staffAccess' to 'auth'
//                     $query->select('id', 'staff_auth_id', 'system_access_id'); // Assuming 'auth_id' links staffAccess to auth
//                 },
//                 'auth.staffAccess.systemAccess' => function ($query) {
//                     // Select columns for the 'systemAccess' model.
//                     // You must include the foreign key that links 'systemAccess' to 'staffAccess'
//                     $query->select('id', 'branch_id', 'hrms'); // Assuming 'staff_access_id' links systemAccess to staffAccess
//                 }
//             ])
//             ->find($id); // This is the key change: use find() to get a single record by its primary key

//         if (!$staff) {
//             // You might want to abort with a 404 or redirect
//             abort(404, 'Staff member not found.');
//         }
//         return view('hrms::index', compact('staff'));
//     }