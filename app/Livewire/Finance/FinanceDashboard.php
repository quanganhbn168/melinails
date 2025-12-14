<?php

namespace App\Livewire\Finance;

use App\Models\TaskReport;
use App\Models\WorkOrderPayment;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Component;
use Carbon\Carbon;

#[Layout('layouts.admin')]
class FinanceDashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $statusFilter = 'all';
    public $dateFilter = 'this_month';

    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingDateFilter() { $this->resetPage(); }

    public function syncLegacyData()
    {
        $reports = TaskReport::where('collected_amount', '>', 0)->get();
        $count = 0;

        foreach ($reports as $report) {
            $exists = WorkOrderPayment::where('task_report_id', $report->id)->exists();
            if (!$exists) {
                // 1. Create ITEM_VALUE (Debt)
                WorkOrderPayment::create([
                    'work_order_id' => $report->task->work_order_id ?? null,
                    'task_report_id' => $report->id,
                    'payment_type' => \App\Enums\PaymentType::ITEM_VALUE,
                    'amount' => $report->collected_amount,
                    'description' => 'Đồng bộ từ báo cáo cũ #' . $report->id,
                    'is_collected' => false,
                    'status' => $this->mapLegacyStatus($report->finance_status),
                    'created_by' => $report->reporter_id,
                    'created_at' => $report->created_at,
                ]);

                // 2. Create COLLECTION (Payment)
                WorkOrderPayment::create([
                    'work_order_id' => $report->task->work_order_id ?? null,
                    'task_report_id' => $report->id,
                    'payment_type' => \App\Enums\PaymentType::COLLECTION,
                    'amount' => $report->collected_amount,
                    'description' => 'Thu tiền (Đồng bộ) #' . $report->id,
                    'is_collected' => true,
                    'payment_method' => $report->payment_method ?? 'cash',
                    'transfer_target' => $report->transfer_target,
                    'status' => $this->mapLegacyStatus($report->finance_status),
                    'created_by' => $report->reporter_id,
                    'collector_id' => $report->reporter_id,
                    'verified_by' => ($report->finance_status == 'verified' || $report->finance_status == 'handed_over') ? auth('admin')->id() : null,
                    'collected_at' => $report->created_at,
                    'created_at' => $report->created_at,
                ]);
                $count++;
            }
        }

        $this->dispatch('alert', ['type' => 'success', 'message' => "Đã đồng bộ $count báo cáo cũ!"]);
    }

    private function mapLegacyStatus($status)
    {
        return match ($status) {
            'verified', 'handed_over' => \App\Enums\PaymentStatus::VERIFIED,
            'cancelled' => \App\Enums\PaymentStatus::CANCELLED,
            default => \App\Enums\PaymentStatus::PENDING,
        };
    }

    public function render()
    {
        $query = WorkOrderPayment::with(['workOrder.customer', 'taskReport.task', 'creator', 'collector'])
            ->orderByDesc('created_at');

        // Filter by Status
        if ($this->statusFilter != 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Filter by Date
        if ($this->dateFilter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->dateFilter == 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        // Calculate Stats
        $stats = WorkOrderPayment::selectRaw("
                SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending_total,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
                
                SUM(CASE WHEN status = 'verified' AND transfer_target = 'company' THEN amount ELSE 0 END) as company_total,
                COUNT(CASE WHEN status = 'verified' AND transfer_target = 'company' THEN 1 END) as company_count,

                SUM(CASE WHEN status = 'verified' AND transfer_target = 'personal' THEN amount ELSE 0 END) as personal_total,
                COUNT(CASE WHEN status = 'verified' AND transfer_target = 'personal' THEN 1 END) as personal_count,

                SUM(CASE WHEN status = 'verified' AND payment_method = 'cash' THEN amount ELSE 0 END) as cash_total,
                COUNT(CASE WHEN status = 'verified' AND payment_method = 'cash' THEN 1 END) as cash_count
            ")
            ->first();

        $reports = $query->paginate(20);

        return view('livewire.finance.finance-dashboard', [
            'reports' => $reports,
            'stats' => $stats
        ]);
    }
}
