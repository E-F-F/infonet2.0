@extends('layouts.default')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard Overview</h1>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">New Report</button>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-500">Total Users</p>
            <p class="text-2xl font-bold text-blue-600">1,205</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-500">Active Sessions</p>
            <p class="text-2xl font-bold text-green-600">89</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-500">Monthly Revenue</p>
            <p class="text-2xl font-bold text-yellow-600">$12,340</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-500">System Errors</p>
            <p class="text-2xl font-bold text-red-600">4</p>
        </div>
    </div>

    <!-- Section -->
    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Recent Activity</h2>
        <div class="bg-white rounded shadow p-4">
            <ul>
                <li class="border-b py-2">[+] Admin added a new user.</li>
                <li class="border-b py-2">[!] Payment processing updated.</li>
                <li class="border-b py-2">[*] New report generated.</li>
                <li class="py-2">[~] Password policy changed.</li>
            </ul>
        </div>
    </div>
@stop
