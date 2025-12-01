<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\TaskItem;
use Livewire\Attributes\Layout;

class WarrantySearch extends Component
{
    public $serial_number = '';
    public $result = null;
    public $searched = false;

    #[Layout('layouts.guest')]
    // Ở đây em return view trực tiếp có HTML cho gọn, khỏi tạo file layout mới
    public function render()
    {
        return view('livewire.public.warranty-search');
    }

    public function search()
    {
        $this->validate([
            'serial_number' => 'required|min:3'
        ], [
            'serial_number.required' => 'Vui lòng nhập số Serial hoặc Mã thiết bị.',
            'serial_number.min' => 'Mã quá ngắn, vui lòng kiểm tra lại.'
        ]);

        $this->searched = true;

        // Tìm thiết bị trùng Serial
        // Join với bảng task để lấy ngày tạo (created_at)
        $this->result = TaskItem::where('serial_number', $this->serial_number)
            ->with('task.workOrder.customer') // Lấy thêm thông tin khách để đối chiếu (nếu cần)
            ->latest() // Nếu 1 mã nhập nhiều lần thì lấy lần gần nhất
            ->first();
    }
}