<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public function addActivityLog($model, $message)
    {
        // Decode existing logs or initialize an empty array
        $logs = [];
        if (!empty($model->activity_logs)) {
            $logs = json_decode($model->activity_logs, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $logs = []; // Reset if JSON is invalid
            }
        }

        // Add new log entry
        $logs[] = [
            'message' => $message,
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'), // Format timestamp
        ];

        // Encode logs back to JSON
        $model->activity_logs = json_encode($logs);
        $model->save();
    }
}
