<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function debug($id)
    {
        $workOrder = WorkOrder::with(['tasks.reports.items', 'tasks.reports'])->find($id);
        return response()->json($workOrder);
    }
}
