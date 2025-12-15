<?php

namespace App\Livewire\Material;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReturnedItem;
use Illuminate\Support\Facades\Auth;

use App\Enums\ReturnedItemStatus;
use App\Enums\SupplierResult;

class ReturnedMaterialList extends Component
{
    use WithPagination;
    
    // ... (keep properties) ...

    public function paginationView()
    {
        return 'livewire::bootstrap'; 
    }

    public $search = '';
    public $filterReason = '';
    public $filterStatus = '';
    public $filterFrom = '';
    public $filterTo = '';

    // Modal state
    public $editingItemId = null;
    
    // Send to Supplier
    public $sendSupplierId;
    public $sendStaffId;
    public $sendNote;

    // Receive From Supplier
    public $receiveResult;
    public $receiveCost;
    public $receiveNote;

    public function mount()
    {
        $this->filterFrom = now()->startOfMonth()->format('Y-m-d');
        $this->filterTo = now()->format('Y-m-d');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterReason', 'filterStatus']);
        $this->filterFrom = now()->startOfMonth()->format('Y-m-d');
        $this->filterTo = now()->format('Y-m-d');
    }

    // --- ACTIONS ---

    public function openSendModal($itemId)
    {
        $item = ReturnedItem::find($itemId);
        if (!$item) return;

        $this->editingItemId = $itemId;
        $this->sendSupplierId = $item->supplier_id;
        $this->sendStaffId = Auth::guard('admin')->id();
        $this->sendNote = '';

        $this->dispatch('open-send-modal');
    }

    public function saveSendToSupplier()
    {
        $this->validate([
            'sendSupplierId' => 'required',
            'sendStaffId' => 'required',
        ]);

        $item = ReturnedItem::find($this->editingItemId);
        if ($item) {
            $item->update([
                'status' => ReturnedItemStatus::SENT_TO_SUPPLIER,
                'supplier_id' => $this->sendSupplierId,
                'sent_to_supplier_by' => $this->sendStaffId,
                'sent_to_supplier_at' => now(),
                'notes' => $this->sendNote ? ($item->notes . "\n[Gửi NCC]: " . $this->sendNote) : $item->notes,
            ]);
            
            $this->dispatch('notify', type: 'success', message: 'Đã cập nhật trạng thái: Gửi NCC');
            $this->dispatch('close-modals');
        }
    }

    public function openReceiveModal($itemId)
    {
        $item = ReturnedItem::find($itemId);
        if (!$item) return;

        $this->editingItemId = $itemId;
        $this->receiveResult = SupplierResult::FIXED->value;
        $this->receiveCost = 0;
        $this->receiveNote = '';

        $this->dispatch('open-receive-modal');
    }

    public function saveReceiveFromSupplier()
    {
        $this->validate([
            'receiveResult' => 'required',
            'receiveCost' => 'numeric|min:0',
        ]);

        $item = ReturnedItem::find($this->editingItemId);
        if ($item) {
            $item->update([
                'status' => ReturnedItemStatus::RETURNED_FROM_SUPPLIER,
                'supplier_result' => $this->receiveResult, // Auto-casted to Enum by Model
                'repair_cost' => $this->receiveCost ?? 0,
                'received_from_supplier_at' => now(),
                'notes' => $this->receiveNote ? ($item->notes . "\n[Nhận từ NCC]: " . $this->receiveNote) : $item->notes,
            ]);

            $this->dispatch('notify', type: 'success', message: 'Đã cập nhật trạng thái: Nhận từ NCC');
            $this->dispatch('close-modals');
        }
    }

    public function markAsClosed($itemId)
    {
        $item = ReturnedItem::find($itemId);
        if ($item) {
            $item->update(['status' => ReturnedItemStatus::RETURNED]);
            $this->dispatch('notify', type: 'success', message: 'Đã hoàn tất xử lý!');
        }
    }

    // ... (updateStatus, openEditModal, saveDetails logic remains mostly same but update statuses if used) ...
    
    public function updateStatus($itemId, $newStatus)
    {
        $item = ReturnedItem::find($itemId);
        if (!$item) return;

        // Try to cast string from UI to Enum
        try {
            $enumStatus = ReturnedItemStatus::from($newStatus);
            $item->status = $enumStatus;
            
            if ($enumStatus === ReturnedItemStatus::RETURNED) {
                $item->returned_by = Auth::guard('admin')->id();
                $item->returned_at = now();
            }
            
            $item->save();
            $this->dispatch('notify', type: 'success', message: 'Đã cập nhật trạng thái!');
        } catch (\ValueError $e) {
            // Invalid status
        }
    }

    public function openEditModal($itemId)
    {
        $item = ReturnedItem::find($itemId);
        if (!$item) return;

        $this->editingItemId = $itemId;
        $this->editSupplierId = $item->supplier_id;
        $this->editNotes = $item->notes ?? '';
        
        $this->dispatch('open-edit-modal');
    }

    public function saveDetails()
    {
        $item = ReturnedItem::find($this->editingItemId);
        if (!$item) return;

        $item->supplier_id = $this->editSupplierId;
        $item->notes = $this->editNotes;
        
        if ($this->editSupplierId && $item->status === ReturnedItemStatus::PENDING) {
            $item->status = ReturnedItemStatus::SENT_TO_SUPPLIER;
        }
        
        $item->save();
        
        $this->reset(['editingItemId', 'editSupplierId', 'editNotes']);
        $this->dispatch('close-edit-modal');
        $this->dispatch('notify', type: 'success', message: 'Đã lưu thông tin!');
    }

    public function render()
    {
        $query = ReturnedItem::with([
            'report.task.workOrder:id,code,title',
            'report.task:id,work_order_id,title',
            'report:id,task_id,created_at',
            'supplier:id,name',
            'returnedByAdmin:id,name'
        ]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterReason) {
            $query->where('reason', $this->filterReason);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterFrom) {
            $query->whereHas('report', fn($q) => $q->whereDate('created_at', '>=', $this->filterFrom));
        }
        if ($this->filterTo) {
            $query->whereHas('report', fn($q) => $q->whereDate('created_at', '<=', $this->filterTo));
        }

        $items = $query->orderByDesc('id')->paginate(20);

        // Stats
        $statsQuery = ReturnedItem::query();
        if ($this->filterFrom) $statsQuery->whereHas('report', fn($q) => $q->whereDate('created_at', '>=', $this->filterFrom));
        if ($this->filterTo) $statsQuery->whereHas('report', fn($q) => $q->whereDate('created_at', '<=', $this->filterTo));

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', ReturnedItemStatus::PENDING)->count(),
            'sent_to_supplier' => (clone $statsQuery)->where('status', ReturnedItemStatus::SENT_TO_SUPPLIER)->count(),
            'returned_from_supplier' => (clone $statsQuery)->where('status', ReturnedItemStatus::RETURNED_FROM_SUPPLIER)->count(),
            'done' => (clone $statsQuery)->whereIn('status', [ReturnedItemStatus::RETURNED, ReturnedItemStatus::CLOSED])->count(),
        ];

        return view('livewire.material.returned-material-list', [
            'items' => $items,
            'stats' => $stats,
            'reasons' => [
                'warranty' => 'Bảo hành',
                'replace' => 'Đổi model',
                'defective' => 'Lỗi nhà SX',
                'upgrade' => 'Nâng cấp',
            ],
            // Pass Enum cases for Dropdowns
            'statuses' => ReturnedItemStatus::cases(),
            'resultOptions' => SupplierResult::cases(),
            'suppliers' => \App\Models\Customer::suppliers()->orderBy('name')->get(['id', 'name']),
            'staffs' => \App\Models\Admin::all(['id', 'name']),
        ])->layout('layouts.admin');
    }
}
