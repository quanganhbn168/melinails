<?php

namespace App\Livewire\Finance;

use App\Models\WorkOrder;
use App\Models\TaskReport;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class WorkOrderFinanceDetail extends Component
{
    public $workOrder;
    public $reports;

    public function mount($id)
    {
        $this->workOrder = WorkOrder::with(['customer', 'tasks.reports' => function($q) {
            $q->where('collected_amount', '>', 0);
        }, 'tasks.reports.reporter'])->findOrFail($id);
        
        $this->refreshReports();
    }

    public function refreshReports()
    {
        // Flatten reports from tasks
        $this->reports = $this->workOrder->tasks->flatMap->reports->where('collected_amount', '>', 0)->sortByDesc('created_at');
    }

    public function verify($reportId)
    {
        $report = TaskReport::find($reportId);
        if ($report) {
            $report->update(['finance_status' => 'verified']);
            session()->flash('message', 'Đã xác nhận tiền về tài khoản.');
            $this->mount($this->workOrder->id); // Refresh data
        }
    }

    public function handover($reportId)
    {
        $report = TaskReport::find($reportId);
        if ($report) {
            $report->update(['finance_status' => 'handed_over']);
            session()->flash('message', 'Đã xác nhận nhận tiền mặt.');
            $this->mount($this->workOrder->id); // Refresh data
        }
    }

    public function render()
    {
        return view('livewire.finance.work-order-finance-detail');
    }
}
