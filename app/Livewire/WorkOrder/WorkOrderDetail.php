<?php

namespace App\Livewire\WorkOrder;

use Livewire\Component;
use App\Models\WorkOrder;
use App\Models\Task; 
use App\Models\TaskReport;
use App\Models\TaskItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url; // Import Url attribute

class WorkOrderDetail extends Component
{
    public $workOrder;
    public $tasks;
    
    #[Url(as: 'tab')] // Bind query param 'tab' to $activeTab
    public $activeTab = 'progress';

    // Financial Data
    // Financial Data
    public $totalCollected = 0;
    public $allItems = [];
    public $allPayments = [];

    // Material Management
    public $showMaterialModal = false;
    public $newMaterial = [
        'task_id' => '',
        'name' => '',
        'serial' => '',
        'quantity' => 1,
        'price' => 0,
    ];

    public function mount($id)
    {
        $this->workOrder = WorkOrder::with(['customer.contacts', 'tasks.reports.items', 'tasks.reports', 'creator'])
            ->findOrFail($id);
        $this->refreshTasks(); 
    }

    public function refreshTasks()
    {
        $this->tasks = $this->workOrder->tasks()->with(['reports.items', 'performer'])->orderBy('id', 'asc')->get();
        $this->calculateFinancials();
    }

    public function calculateFinancials()
    {
        $this->totalCollected = 0;
        $this->allItems = [];
        $this->allPayments = [];

        foreach ($this->tasks as $task) {
            foreach ($task->reports as $report) {
                // 1. Tổng hợp vật tư
                foreach ($report->items as $item) {
                    $this->allItems[] = [
                        'id' => $item->id,
                        'name' => $item->item_name,
                        'serial' => $item->serial_number,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'task_id' => $task->id,
                        'report_date' => $report->created_at
                    ];
                }

                // 2. Tổng hợp thanh toán
                if ($report->collected_amount > 0) {
                    // Chỉ tính những khoản đã được kế toán xác nhận (verified hoặc handed_over)
                    if (in_array($report->finance_status, ['verified', 'handed_over'])) {
                        $this->totalCollected += $report->collected_amount;
                    }
                    
                    $this->allPayments[] = [
                        'amount' => $report->collected_amount,
                        'method' => $report->payment_method ?? 'cash',
                        'target' => $report->transfer_target,
                        'reporter' => $report->reporter->name ?? 'N/A',
                        'date' => $report->created_at,
                        'status' => $report->finance_status
                    ];
                }
            }
        }
    }

    // --- MATERIAL MANAGEMENT ---

    public function openMaterialModal()
    {
        $this->newMaterial = [
            'task_id' => $this->tasks->first()->id ?? '',
            'name' => '',
            'serial' => '',
            'quantity' => 1,
            'price' => 0,
        ];
        $this->showMaterialModal = true;
    }

    public function saveMaterial()
    {
        $this->validate([
            'newMaterial.task_id' => 'required|exists:tasks,id',
            'newMaterial.name' => 'required|string|max:255',
            'newMaterial.quantity' => 'required|integer|min:1',
            'newMaterial.price' => 'required|numeric|min:0',
        ]);

        // 1. Tạo (hoặc tìm) báo cáo để gắn vật tư
        // Ở đây ta tạo mới 1 báo cáo "System" để ghi nhận việc Admin thêm vật tư
        $report = TaskReport::create([
            'task_id' => $this->newMaterial['task_id'],
            'reporter_id' => auth('admin')->id(),
            'content' => 'Cập nhật vật tư từ Admin',
            'is_completed' => false,
        ]);

        // 2. Tạo vật tư
        TaskItem::create([
            'task_report_id' => $report->id,
            'item_name' => $this->newMaterial['name'],
            'serial_number' => $this->newMaterial['serial'],
            'quantity' => $this->newMaterial['quantity'],
            'price' => $this->newMaterial['price'],
        ]);

        $this->showMaterialModal = false;
        session()->flash('message', 'Đã thêm vật tư thành công.');
        $this->refreshTasks();
    }

    public function deleteMaterial($itemId)
    {
        $item = TaskItem::find($itemId);
        if ($item) {
            // Nếu báo cáo chỉ có 1 vật tư này và nội dung là "Cập nhật vật tư từ Admin" thì xóa luôn báo cáo cho sạch
            $report = $item->report;
            $item->delete();

            if ($report && $report->items()->count() == 0 && $report->content == 'Cập nhật vật tư từ Admin') {
                $report->delete();
            }

            session()->flash('message', 'Đã xóa vật tư.');
            $this->refreshTasks();
        }
    }

    // --- HÀM MỞ LẠI TASK (ADMIN ONLY) ---
    public function reopenTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task && $task->status === \App\Enums\TaskStatus::COMPLETED) {
            $task->update(['status' => \App\Enums\TaskStatus::PROCESSING]);
            
            // Nếu WorkOrder đang ở trạng thái chờ duyệt (pending_approval) thì cũng phải quay về processing
            if($this->workOrder->status === \App\Enums\WorkOrderStatus::PENDING_APPROVAL) {
                $this->workOrder->update(['status' => \App\Enums\WorkOrderStatus::PROCESSING]);
            }

            session()->flash('message', 'Đã mở lại công việc thành công.');
            $this->refreshTasks(); // Load lại danh sách để cập nhật giao diện
        }
    }

    public function render()
    {
        return view('livewire.work-order.work-order-detail')
            ->layout(auth('admin')->user()->layout);
    }
}