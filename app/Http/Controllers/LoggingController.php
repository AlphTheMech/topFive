<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class LoggingController extends Controller
{
    public function getLogging()
    {
        $log = LogResource::collection(Activity::paginate(100));
        return response()->json([
            'data' => [
                'items' => $log,
                'paginate' => [
                    'total' => $log->total(),
                    'per_page' => $log->perPage(),
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
    public function deleteAllLog()
    {
        Activity::all()->delete();

        return response()->json([
            'data' => [
                'code' => 200,
                'message' => "Информация о логировании успешно удалена"
            ]
        ], 200);
    }
    // public function deletingRecordsOlderThan14Days()
    // {
    //     Activity::where('created_at', '<', Carbon::now()->subDays(21))->get()->delete();
    // }
}
