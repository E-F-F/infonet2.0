<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff Member</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            /* Light gray background */
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-4xl border border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Add New Staff Member</h1>

            <!-- Session Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Staff Registration Form -->
            <form action="{{ route('hrms.staff.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Authentication Details Section -->
                <div class="border border-gray-300 p-6 rounded-lg bg-gray-50">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Authentication Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter username" required>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter password" required>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="border border-gray-300 p-6 rounded-lg bg-gray-50">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="firstName" id="firstName" value="{{ old('firstName') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., John" required>
                        </div>
                        <div>
                            <label for="middleName" class="block text-sm font-medium text-gray-700 mb-1">Middle
                                Name</label>
                            <input type="text" name="middleName" id="middleName" value="{{ old('middleName') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., David">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="lastName" id="lastName" value="{{ old('lastName') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., Doe" required>
                        </div>
                        <div class="md:col-span-3">
                            <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="fullName" id="fullName" value="{{ old('fullName') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., John David Doe" required>
                        </div>
                        <div>
                            <label for="ic_no" class="block text-sm font-medium text-gray-700 mb-1">IC No./Passport
                                No. <span class="text-red-500">*</span></label>
                            <input type="text" name="ic_no" id="ic_no" value="{{ old('ic_no') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., 901231-14-5678" required>
                        </div>
                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth
                                <span class="text-red-500">*</span></label>
                            <input type="date" name="dob" id="dob" value="{{ old('dob') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span
                                    class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Marital
                                Status <span class="text-red-500">*</span></label>
                            <select name="marital_status" id="marital_status" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Marital Status</option>
                                <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>
                                    Single</option>
                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>
                                    Married</option>
                                <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>
                                    Divorced</option>
                                <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>
                                    Widowed</option>
                            </select>
                        </div>
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nationality" id="nationality"
                                value="{{ old('nationality') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., Malaysian" required>
                        </div>
                        <div>
                            <label for="religion"
                                class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                            <input type="text" name="religion" id="religion" value="{{ old('religion') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., Islam, Christianity">
                        </div>
                        <div>
                            <label for="race" class="block text-sm font-medium text-gray-700 mb-1">Race</label>
                            <input type="text" name="race" id="race" value="{{ old('race') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., Malay, Chinese, Indian">
                        </div>
                        <div>
                            <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-1">Blood
                                Group</label>
                            <input type="text" name="blood_group" id="blood_group"
                                value="{{ old('blood_group') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., A+, O-">
                        </div>
                        <div class="md:col-span-3">
                            <label for="home_address" class="block text-sm font-medium text-gray-700 mb-1">Home
                                Address <span class="text-red-500">*</span></label>
                            <textarea name="home_address" id="home_address" rows="3" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter full home address">{{ old('home_address') }}</textarea>
                        </div>
                        <div class="md:col-span-3">
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Image
                                URL</label>
                            <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., https://example.com/profile.jpg">
                        </div>
                    </div>
                </div>

                <!-- Employment Details Section -->
                <div class="border border-gray-300 p-6 rounded-lg bg-gray-50">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Employment Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Branch <span
                                    class="text-red-500">*</span></label>
                            <select name="branch_id" id="branch_id" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Branch</option>
                                <!-- In a real application, these options would be dynamically loaded from the 'branches' table -->
                                <option value="1" {{ old('branch_id') == '1' ? 'selected' : '' }}>Main Office
                                </option>
                                <option value="2" {{ old('branch_id') == '2' ? 'selected' : '' }}>North Branch
                                </option>
                                <option value="3" {{ old('branch_id') == '3' ? 'selected' : '' }}>South Branch
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="hrms_designation_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Designation <span
                                    class="text-red-500">*</span></label>
                            <select name="hrms_designation_id" id="hrms_designation_id" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Designation</option>
                                <!-- Dynamically loaded from 'hrms_designations' table -->
                                <option value="1" {{ old('hrms_designation_id') == '1' ? 'selected' : '' }}>
                                    Software Engineer</option>
                                <option value="2" {{ old('hrms_designation_id') == '2' ? 'selected' : '' }}>HR
                                    Manager</option>
                                <option value="3" {{ old('hrms_designation_id') == '3' ? 'selected' : '' }}>
                                    Accountant</option>
                            </select>
                        </div>
                        <div>
                            <label for="hrms_leave_rank_id" class="block text-sm font-medium text-gray-700 mb-1">Leave
                                Rank <span class="text-red-500">*</span></label>
                            <select name="hrms_leave_rank_id" id="hrms_leave_rank_id" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Leave Rank</option>
                                <!-- Dynamically loaded from 'hrms_leave_ranks' table -->
                                <option value="1" {{ old('hrms_leave_rank_id') == '1' ? 'selected' : '' }}>Senior
                                    Staff</option>
                                <option value="2" {{ old('hrms_leave_rank_id') == '2' ? 'selected' : '' }}>Junior
                                    Staff</option>
                            </select>
                        </div>
                        <div>
                            <label for="hrms_pay_group_id" class="block text-sm font-medium text-gray-700 mb-1">Pay
                                Group <span class="text-red-500">*</span></label>
                            <select name="hrms_pay_group_id" id="hrms_pay_group_id" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Pay Group</option>
                                <!-- Dynamically loaded from 'hrms_pay_groups' table -->
                                <option value="1" {{ old('hrms_pay_group_id') == '1' ? 'selected' : '' }}>Monthly
                                    Salary</option>
                                <option value="2" {{ old('hrms_pay_group_id') == '2' ? 'selected' : '' }}>Hourly
                                    Wage</option>
                            </select>
                        </div>
                        <div>
                            <label for="hrms_appraisal_type_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Appraisal Type <span
                                    class="text-red-500">*</span></label>
                            <select name="hrms_appraisal_type_id" id="hrms_appraisal_type_id" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Appraisal Type</option>
                                <!-- Dynamically loaded from 'hrms_appraisal_types' table -->
                                <option value="1" {{ old('hrms_appraisal_type_id') == '1' ? 'selected' : '' }}>
                                    Annual Review</option>
                                <option value="2" {{ old('hrms_appraisal_type_id') == '2' ? 'selected' : '' }}>
                                    Quarterly Review</option>
                            </select>
                        </div>
                        <div>
                            <label for="employee_number" class="block text-sm font-medium text-gray-700 mb-1">Employee
                                Number <span class="text-red-500">*</span></label>
                            <input type="text" name="employee_number" id="employee_number"
                                value="{{ old('employee_number') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., EMP001" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="joining_date" class="block text-sm font-medium text-gray-700 mb-1">Joining
                                Date <span class="text-red-500">*</span></label>
                            <input type="date" name="joining_date" id="joining_date"
                                value="{{ old('joining_date') }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Add Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
