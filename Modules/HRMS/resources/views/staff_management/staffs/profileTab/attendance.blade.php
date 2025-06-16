<!-- Attendance History Tab -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="p-6 pb-3 flex justify-between items-center border-b border-gray-100">
    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
        <path d="M6 2a1 1 0 100 2h8a1 1 0 100-2H6zM3 6a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6z" />
      </svg>
      Attendance History - June 2025
    </h2>
  </div>

  <div class="px-6 py-4">
    <!-- Weekdays header -->
    <div class="grid grid-cols-7 text-xs text-center text-gray-500 font-medium mb-2">
      <div>Sun</div>
      <div>Mon</div>
      <div>Tue</div>
      <div>Wed</div>
      <div>Thu</div>
      <div>Fri</div>
      <div>Sat</div>
    </div>

    <!-- Calendar grid -->
    <div class="grid grid-cols-7 gap-2 text-sm">
      <!-- Empty cells before 1st day (e.g., June 1st is Saturday, need 6 blanks) -->
      <div></div><div></div><div></div><div></div><div></div><div></div>

      <!-- Example attendance days -->
      <div class="border rounded-lg h-20 p-1">
        <div class="text-xs text-gray-500">1</div>
        <div class="text-xs text-green-600 bg-green-100 px-1 rounded mt-1 inline-block">Present</div>
      </div>
      <div class="border rounded-lg h-20 p-1">
        <div class="text-xs text-gray-500">2</div>
        <div class="text-xs text-yellow-600 bg-yellow-100 px-1 rounded mt-1 inline-block">Late</div>
      </div>
      <div class="border rounded-lg h-20 p-1">
        <div class="text-xs text-gray-500">3</div>
        <div class="text-xs text-red-600 bg-red-100 px-1 rounded mt-1 inline-block">Absent</div>
      </div>
      <!-- Continue for each day up to 30/31 -->
    </div>

    <div class="mt-4 text-xs text-gray-500 flex gap-4">
      <div><span class="inline-block w-3 h-3 bg-green-400 rounded-full mr-1"></span>Present</div>
      <div><span class="inline-block w-3 h-3 bg-yellow-400 rounded-full mr-1"></span>Late</div>
      <div><span class="inline-block w-3 h-3 bg-red-400 rounded-full mr-1"></span>Absent</div>
    </div>
  </div>
</div>
