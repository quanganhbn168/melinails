<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\WarrantyDevice;
use App\Models\WarrantyService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class CustomerDetail extends Component
{
    public $customer;
    public $stats = [];
    public $activeTab = 'work_orders';

    public function mount($id)
    {
        $this->customer = Customer::with('contacts')->findOrFail($id);
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // 1. Tổng tiền
        $totalSpent = DB::table('task_reports')
            ->join('tasks', 'task_reports.task_id', '=', 'tasks.id')
            ->join('work_orders', 'tasks.work_order_id', '=', 'work_orders.id')
            ->where('work_orders.customer_id', $this->customer->id)
            ->sum('task_reports.collected_amount');

        // 2. Số thiết bị còn bảo hành
        $activeWarrantiesCount = WarrantyDevice::query()
            ->join('task_items', 'warranty_devices.task_item_id', '=', 'task_items.id')
            ->join('task_reports', 'task_items.task_report_id', '=', 'task_reports.id')
            ->join('tasks', 'task_reports.task_id', '=', 'tasks.id')
            ->join('work_orders', 'tasks.work_order_id', '=', 'work_orders.id')
            ->where('work_orders.customer_id', $this->customer->id)
            ->where('warranty_devices.status', 'active')
            ->where('warranty_devices.expiration_date', '>=', now())
            ->count();

        $lastOrder = $this->customer->workOrders()->latest()->first();

        $this->stats = [
            'total_spent' => $totalSpent,
            'total_orders' => $this->customer->workOrders()->count(),
            'active_warranties' => $activeWarrantiesCount,
            'last_date' => $lastOrder ? $lastOrder->created_at->format('d/m/Y') : 'Chưa có',
        ];
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        // 1. Lịch sử Phiếu việc
        $workOrders = $this->customer->workOrders()
            ->withCount('tasks')
            ->latest()
            ->get();

        // 2. Bảo hành Thiết bị (Lẻ)
        $warranties = WarrantyDevice::query()
            ->select('warranty_devices.*', 'tasks.work_order_id', 'work_orders.code as wo_code')
            ->join('task_items', 'warranty_devices.task_item_id', '=', 'task_items.id')
            ->join('task_reports', 'task_items.task_report_id', '=', 'task_reports.id')
            ->join('tasks', 'task_reports.task_id', '=', 'tasks.id')
            ->join('work_orders', 'tasks.work_order_id', '=', 'work_orders.id')
            ->where('work_orders.customer_id', $this->customer->id)
            ->orderBy('warranty_devices.expiration_date', 'desc')
            ->get();

        // 3. Bảo hành Dịch vụ (Gói)
        $serviceWarranties = WarrantyService::query()
            ->select('warranty_services.*', 'work_orders.code as wo_code', 'work_orders.title as wo_title')
            ->join('work_orders', 'warranty_services.work_order_id', '=', 'work_orders.id')
            ->where('work_orders.customer_id', $this->customer->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('livewire.customer.customer-detail', [
            'workOrders' => $workOrders,
            'warranties' => $warranties,
            'serviceWarranties' => $serviceWarranties
        ])->layout(auth('admin')->user()->layout);
    }
}