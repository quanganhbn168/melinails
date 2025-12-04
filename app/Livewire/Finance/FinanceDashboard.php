<?php

namespace App\Livewire\Finance;

use App\Models\TaskReport;
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

    public function render()
    {
        $query = TaskReport::with(['task.workOrder.customer', 'reporter'])
            ->where('collected_amount', '>', 0);

        // Filter by Status
        if ($this->statusFilter != 'all') {
            $query->where('finance_status', $this->statusFilter);
        }

        // Filter by Date
        if ($this->dateFilter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->dateFilter == 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        // Clone query for stats to avoid pagination issues
        $statsQuery = clone $query;
        
        // Calculate Stats (based on current filters or global? Usually global or filtered. Let's do filtered for now, or maybe global stats are better for the top cards?)
        // Let's do Global Stats for the cards to show overall health, and Filtered list for the table.
        
        // Calculate Stats (New Logic: Pending, Company, Personal, Cash)
        $stats = TaskReport::where('collected_amount', '>', 0)
            ->selectRaw("
                SUM(CASE WHEN finance_status = 'pending' THEN collected_amount ELSE 0 END) as pending_total,
                COUNT(CASE WHEN finance_status = 'pending' THEN 1 END) as pending_count,
                
                SUM(CASE WHEN finance_status = 'verified' AND transfer_target = 'company' THEN collected_amount ELSE 0 END) as company_total,
                COUNT(CASE WHEN finance_status = 'verified' AND transfer_target = 'company' THEN 1 END) as company_count,

                SUM(CASE WHEN finance_status = 'verified' AND transfer_target = 'personal' THEN collected_amount ELSE 0 END) as personal_total,
                COUNT(CASE WHEN finance_status = 'verified' AND transfer_target = 'personal' THEN 1 END) as personal_count,

                SUM(CASE WHEN finance_status = 'handed_over' THEN collected_amount ELSE 0 END) as cash_total,
                COUNT(CASE WHEN finance_status = 'handed_over' THEN 1 END) as cash_count
            ")
            ->first();

        $reports = $query->orderByDesc('created_at')->paginate(20);

        return view('livewire.finance.finance-dashboard', [
            'reports' => $reports,
            'stats' => $stats
        ]);
    }
}
