$wo = App\Models\WorkOrder::with(['tasks.reports.items'])->find(5);
echo "WorkOrder: " . $wo->code . "\n";
foreach($wo->tasks as $task) {
    echo "Task ID: " . $task->id . "\n";
    foreach($task->reports as $report) {
        echo "  Report ID: " . $report->id . ", Collected: " . $report->collected_amount . ", Status: " . $report->finance_status . "\n";
        echo "  Items Count: " . $report->items->count() . "\n";
        foreach($report->items as $item) {
            echo "    Item: " . $item->item_name . ", Price: " . $item->price . ", Qty: " . $item->quantity . "\n";
        }
    }
}
