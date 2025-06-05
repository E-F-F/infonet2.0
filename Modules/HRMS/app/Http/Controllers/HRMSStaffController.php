<?php

namespace Modules\HRMS\Http\Controllers;

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

class HRMSStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // This is a placeholder. You might display a list of staff here.
        // For now, let's assume it renders a view for adding staff if no other method is specified.
        return view('hrms::staff.create'); // Assuming a view like 'resources/views/hrms/staff/create.blade.php'
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This method directly prepares and shows the form for adding staff.
        // This would render the HTML form you provided earlier.
        return view('hrms::staff.create'); // Assuming your form is in 'resources/views/hrms/staff/create.blade.php'
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Define validation rules for both personal and employment data
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
            'branch_id'             => 'required|exists:branches,id',
            'hrms_designation_id'   => 'required|exists:hrms_designations,id',
            'hrms_leave_rank_id'    => 'required|exists:hrms_leave_ranks,id',
            'hrms_pay_group_id'     => 'required|exists:hrms_pay_groups,id',
            'hrms_appraisal_type_id' => 'required|exists:hrms_appraisal_types,id',
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
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('hrms::show');
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
