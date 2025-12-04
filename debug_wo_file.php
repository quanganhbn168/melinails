$wo = App\Models\WorkOrder::with(['tasks.reports.items'])->find(5);
$output = "WorkOrder: " . $wo->code . "\n";
foreach($wo->tasks as $task) {
    $output .= "Task ID: " . $task->id . "\n";
    foreach($task->reports as $report) {
        $output .= "  Report ID: " . $report->id . ", Collected: " . $report->collected_amount . ", Status: " . $report->finance_status . "\n";
        $output .= "  Items Count: " . $report->items->count() . "\n";
        foreach($report->items as $item) {
            $output .= "    Item: " . $item->item_name . ", Price: " . ($item->price ?? 'NULL') . ", Qty: " . $item->quantity . "\n";
        }
    }
}
file_put_contents('debug_output.txt', $output);
