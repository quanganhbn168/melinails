<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printWorkOrder($id)
    {
        $workOrder = WorkOrder::with(['customer', 'tasks.reports.items', 'tasks.reports', 'creator'])
            ->findOrFail($id);

        // Calculate totals
        $totalAmount = 0;
        $totalCollected = 0;
        $items = [];
        
        $totalItemValue = 0;
        $totalReportedCollection = 0;

        foreach ($workOrder->tasks as $task) {
            foreach ($task->reports as $report) {
                foreach ($report->items as $item) {
                    $itemTotal = $item->quantity * $item->price;
                    $totalItemValue += $itemTotal;
                    $items[] = [
                        'name' => $item->item_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $itemTotal,
                    ];
                }
                
                if ($report->collected_amount > 0) {
                    $totalReportedCollection += $report->collected_amount;
                    if (in_array($report->finance_status, ['verified', 'handed_over'])) {
                        $totalCollected += $report->collected_amount;
                    }
                }
            }
        }

        $totalAmount = $totalItemValue > 0 ? $totalItemValue : $totalReportedCollection;
        $balance = $totalAmount - $totalCollected;

        return view('admin.print.invoice', compact('workOrder', 'items', 'totalAmount', 'totalCollected', 'balance'));
    }
}