<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LoggingController extends Controller
{
    public function getLogging()
    {
        return ActivityLog::paginate(100);
    }
}
