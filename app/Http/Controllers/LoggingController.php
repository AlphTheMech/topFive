<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class LoggingController extends Controller
{
    /**
     * getLogging
     *
     * @return void
     */
    public function getLogging()
    {
        $log = LogResource::collection(Activity::paginate(100));
        return response()->json([
            'data' => [
                'items' => $log,
                'paginate' => [
                    'total' => $log->total(),
                    'per_page' => $log->lastPage() != $log->currentPage()  ? $log->currentPage() + 1 : $log->currentPage(),
                    'current_page' => $log->currentPage(),
                    'last_page' => $log->lastPage(),
                    'from' => $log->firstItem(),
                    'to' => $log->lastItem(),
                    'count' => $log->count(),
                    'total_pages' => $log->lastPage()
                ],
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }
    /**
     * deleteAllLog
     *
     * @return void
     */
    public function deleteAllLog()
    {
        Activity::truncate();
        Activity::create([
            'log_name' => 'Удаление лога',
            'description' => 'deleted',
            'subject_type' => 'App\Models\User',
            'event' => null,
            'subject_id' => 62,
            'causer_type' => 'App\Models\User',
            'causer_id' => auth('sanctum')->user()->id,
            'properties' => null,
            'batch_uuid' => null,
        ]);
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => "Информация о логировании успешно удалена"
            ]
        ], 200);
    }
}
