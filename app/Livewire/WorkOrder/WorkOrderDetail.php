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
    public $allReports = [];
    public $allReturnedItems = [];

    // Material Management
    public $showMaterialModal = false;
    public $newMaterial = [
        'task_id' => '',
        'name' => '',
        'serial' => '',
        'quantity' => 1,
        'price' => 0,
    ];

    // Additional Task Modal
    public $showAdditionalTaskModal = false;
    public $parentTaskId = null;
    public $newAdditionalTask = [
        'title' => '',
        'description' => '',
        'assignee_id' => '',
    ];

    public function mount($id)
    {
        $this->workOrder = WorkOrder::with(['customer.contacts', 'tasks.reports.items', 'tasks.reports.returnedItems', 'tasks.reports', 'creator', 'activityLogs.user', 'attachments'])
            ->findOrFail($id);
        $this->refreshTasks(); 
    }

    public function refreshTasks()
    {
        $this->tasks = $this->workOrder->tasks()->with(['reports.items', 'reports.returnedItems', 'performer', 'performers'])->orderBy('id', 'asc')->get();
        
        // Aggregate all reports for History tab
        $this->allReports = $this->workOrder->tasks
            ->flatMap(function($task) { return $task->reports; })
            ->sortByDesc('created_at');

        // Aggregate returned items
        $this->allReturnedItems = [];
        foreach ($this->tasks as $task) {
            foreach ($task->reports as $report) {
                foreach ($report->returnedItems as $item) {
                    $this->allReturnedItems[] = [
                        'id' => $item->id,
                        'name' => $item->item_name,
                        'serial' => $item->serial_number,
                        'reason' => $item->reason_label,
                        'condition' => $item->condition_note,
                        'report_date' => $report->created_at,
                        'reporter' => $report->reporter->name ?? 'N/A',
                    ];
                }
            }
        }

        $this->calculateFinancials();
    }

    public function calculateFinancials()
    {
        $this->totalCollected = 0;
        $this->allItems = [];
        $this->allPayments = [];

        // 1. Lấy vật tư từ task reports (giữ nguyên)
        foreach ($this->tasks as $task) {
            foreach ($task->reports as $report) {
                foreach ($report->items as $item) {
                    $this->allItems[] = [
                        'id' => $item->id,
                        'name' => $item->item_name,
                        'serial' => $item->serial_number,
                        'quantity' => $item->quantity,
                        'price' => $item->price ?? 0,
                        'task_id' => $task->id,
                        'report_date' => $report->created_at
                    ];
                }
            }
        }

        // 2. Lấy payments từ bảng work_order_payments MỚI
        $payments = $this->workOrder->payments()
            ->with(['creator', 'collector', 'verifier'])
            ->orderByDesc('created_at')
            ->get();

        foreach ($payments as $payment) {
            // Tính tổng tiền đã thu (bất kể trạng thái verified)
            if ($payment->is_collected) {
                $this->totalCollected += $payment->amount;
            }

            $this->allPayments[] = [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'description' => $payment->description,
                'payment_type' => $payment->payment_type,
                'method' => $payment->payment_method ?? 'cash',
                'target' => $payment->transfer_target,
                'is_collected' => $payment->is_collected,
                'status' => $payment->status,
                'created_by' => $payment->creator?->name ?? 'N/A',
                'collector' => $payment->collector?->name,
                'verified_by' => $payment->verifier?->name,
                'date' => $payment->created_at,
                'collected_at' => $payment->collected_at,
                'verified_at' => $payment->verified_at,
            ];
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

    // --- HÀM HOÀN THÀNH NHANH TASK (ADMIN) ---
    public function quickFinishTask($taskId)
    {
        $task = Task::find($taskId);
        
        // Chỉ cho phép nếu task chưa xong
        if ($task && $task->status !== \App\Enums\TaskStatus::COMPLETED) {
            
            // 1. Cập nhật trạng thái Task
            $task->update(['status' => \App\Enums\TaskStatus::COMPLETED]);

            // 2. Kiểm tra xem có báo cáo nào chưa?
            if ($task->reports()->count() == 0) {
                 $this->dispatch('alert', ['type' => 'error', 'message' => 'Nhiệm vụ chưa có báo cáo nào, không thể hoàn thành!']);
                 return;
            }

            // 3. Tạo báo cáo tự động (System Log)
            TaskReport::create([
                'task_id' => $task->id,
                'reporter_id' => auth('admin')->id(),
                'content' => 'Hoàn thành bởi Admin',
                'is_completed' => true,
            ]);

            // 3. Kiểm tra xem tất cả task đã xong chưa?
            $allTasksCompleted = $this->workOrder->tasks()->where('status', '!=', \App\Enums\TaskStatus::COMPLETED)->count() == 0;
            
            if ($allTasksCompleted) {
                // Nếu xong hết rồi thì không tự động đóng WorkOrder like Worker, 
                // mà để Admin tự quyết định nút "Duyệt". 
                // Hoặc có thể tự chuyển sang Pending Approval.
                // Tạm thời giữ nguyên trạng thái WorkOrder để Admin review.
            }

            session()->flash('message', 'Đã đánh dấu hoàn thành nhiệm vụ.');
            $this->refreshTasks();
        }
    }

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

    public function updateWorkOrderStatus($status)
    {
        $statusEnum = \App\Enums\WorkOrderStatus::tryFrom($status);
        if ($statusEnum) {
            $this->workOrder->update(['status' => $statusEnum]);
            session()->flash('message', 'Đã cập nhật trạng thái đơn hàng.');
        }
    }

    // --- ADDITIONAL TASK MODAL METHODS ---

    #[\Livewire\Attributes\On('openAdditionalTaskModal')]
    public function openAdditionalTaskModal($parentTaskId)
    {
        $this->parentTaskId = $parentTaskId;
        $this->newAdditionalTask = [
            'title' => '',
            'description' => '',
            'assignee_id' => auth('admin')->id(), // Mặc định gán cho người tạo
        ];
        $this->showAdditionalTaskModal = true;
    }

    public function closeAdditionalTaskModal()
    {
        $this->showAdditionalTaskModal = false;
        $this->parentTaskId = null;
        $this->reset('newAdditionalTask');
    }

    public function createAdditionalTask()
    {
        $this->validate([
            'newAdditionalTask.title' => 'required|string|max:255',
            'newAdditionalTask.assignee_id' => 'required|exists:admins,id',
        ], [
            'newAdditionalTask.title.required' => 'Vui lòng nhập tiêu đề công việc.',
            'newAdditionalTask.assignee_id.required' => 'Vui lòng chọn người thực hiện.',
        ]);

        $parentTask = Task::find($this->parentTaskId);
        if (!$parentTask) {
            session()->flash('error', 'Không tìm thấy task gốc.');
            return;
        }

        // Tạo task phát sinh
        $newTask = Task::create([
            'work_order_id' => $this->workOrder->id,
            'parent_task_id' => $this->parentTaskId,
            'title' => $this->newAdditionalTask['title'],
            'report_content' => $this->newAdditionalTask['title'], // Backward compatibility
            'description' => $this->newAdditionalTask['description'] ?? '',
            'performer_id' => $this->newAdditionalTask['assignee_id'],
            'status' => \App\Enums\TaskStatus::PENDING,
            'is_additional' => true,
        ]);

        session()->flash('message', 'Đã tạo việc phát sinh: ' . $newTask->title);
        $this->closeAdditionalTaskModal();
        $this->refreshTasks();
    }

    public function render()
    {
        return view('livewire.work-order.work-order-detail')
            ->layout(auth('admin')->user()->layout);
    }
}