<?php

namespace App\Livewire\WorkOrder;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class MyWorkOrders extends Component
{
    public function render()
    {
        // Lấy user hiện tại
        $user = Auth::guard('admin')->user();
        
        // Lấy tham số filter từ URL (mặc định là 'active')
        $filter = request()->input('filter', 'active');

        $query = $user->assignedWorkOrders()
            ->with(['customer', 'tasks']);

        // LOGIC LỌC
        if ($filter == 'active') {
            // Chỉ lấy các job CHƯA hoàn thành và CHƯA hủy
            $query->whereNotIn('status', [
                \App\Enums\WorkOrderStatus::COMPLETED, 
                \App\Enums\WorkOrderStatus::CANCELLED
            ]);
        } 
        // Nếu filter == 'all' thì không where status -> lấy hết

        // LOGIC SẮP XẾP
        // 1. Ưu tiên (Cao -> Thấp)
        // 2. Ngày tạo (Mới nhất -> Cũ nhất)
        $orders = $query->orderBy('priority', 'desc') 
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('livewire.work-order.my-work-orders', [
            'orders' => $orders,
            'filter' => $filter
        ])->layout(auth('admin')->user()->layout);
    }
}