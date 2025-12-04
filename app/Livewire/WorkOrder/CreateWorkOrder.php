<?php

namespace App\Livewire\WorkOrder;

use Livewire\Component;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\CustomerContact;
use App\Models\Admin;
use App\Models\Task; // Gọi Model Task
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

class CreateWorkOrder extends Component
{
    // --- Biến cho Job (Work Order) ---
    public $title;
    public $description;
    public $assignee_ids = []; 
    public $priority = 'medium';
    // --- MỚI: Biến cho Thông tin thi công (Site Info) ---
    public $site_address;
    public $contact_person;
    public $contact_phone;

    // --- MỚI: Biến cho Danh sách nhiệm vụ con ---
    public $task_list = [
        ['content' => '', 'note' => ''] // Mặc định có 1 dòng trắng
    ];

    // --- Biến cho Khách hàng ---
    public $is_new_customer = false;
    public $search_customer = '';
    public $selected_customer_id = null;
    public $selected_customer_name = ''; 
    
    // Nhập khách mới
    public $new_customer_name;
    public $new_customer_phone;
    public $new_customer_address;

    #[Layout('layouts.admin')] 
    public function render()
    {
        $customers = [];
        if (!$this->is_new_customer && strlen($this->search_customer) > 1) {
            $customers = Customer::query()
                ->with('contacts')
                ->where('name', 'like', '%' . $this->search_customer . '%')
                ->orWhereHas('contacts', function ($q) {
                    $q->where('value', 'like', '%' . $this->search_customer . '%');
                })
                ->take(10)->get();
        }
        $staffs = Admin::all();

        return view('livewire.work-order.create-work-order', [
            'customers' => $customers,
            'staffs' => $staffs
        ]);
    }

    // --- Logic thêm/xóa dòng nhiệm vụ ---
    public function addTaskRow()
    {
        $this->task_list[] = ['content' => '', 'note' => ''];
    }

    public function removeTaskRow($index)
    {
        unset($this->task_list[$index]);
        $this->task_list = array_values($this->task_list); // Đánh lại index
    }

    public function selectCustomer($id, $name)
    {
        $this->selected_customer_id = $id;
        $this->selected_customer_name = $name;
        $this->search_customer = '';

        // --- MỚI: Tự động điền thông tin thi công lấy từ khách ---
        // Tìm sđt và địa chỉ chính của khách để gợi ý vào ô thi công
        $customer = Customer::with('contacts')->find($id);
        if ($customer) {
            $this->contact_person = $customer->name; // Mặc định là tên khách
            
            $phone = $customer->contacts->where('type', 'phone')->where('is_primary', true)->first();
            $this->contact_phone = $phone ? $phone->value : '';

            $address = $customer->contacts->where('type', 'address')->where('is_primary', true)->first();
            $this->site_address = $address ? $address->value : '';
        }
    }

    public function clearSelectedCustomer()
    {
        $this->selected_customer_id = null;
        $this->selected_customer_name = '';
        // Clear cả thông tin thi công để nhập lại
        $this->reset(['site_address', 'contact_person', 'contact_phone']);
    }

    public function toggleNewCustomer()
    {
        $this->is_new_customer = !$this->is_new_customer;
        $this->reset(['selected_customer_id', 'selected_customer_name', 'search_customer', 
                      'new_customer_name', 'new_customer_phone', 'new_customer_address',
                      'site_address', 'contact_person', 'contact_phone']); 
    }

    // Khi gõ thông tin khách mới, tự động fill xuống thông tin thi công cho tiện
    public function updatedNewCustomerName($value) { $this->contact_person = $value; }
    public function updatedNewCustomerPhone($value) { $this->contact_phone = $value; }
    public function updatedNewCustomerAddress($value) { $this->site_address = $value; }

    public function save()
    {
        $rules = [
            'title' => 'required|min:5',
            'assignee_ids' => 'required|array|min:1',
            'priority' => 'required|in:low,medium,high,urgent',
            // Validate thông tin thi công
            'site_address' => 'required',
            'contact_person' => 'required',
            'contact_phone' => 'required',
            // Validate danh sách nhiệm vụ
            'task_list.*.content' => 'required|min:3',

        ];

        if ($this->is_new_customer) {
            $rules['new_customer_name'] = 'required';
            $rules['new_customer_phone'] = 'required';
        } else {
            $rules['selected_customer_id'] = 'required';
        }

        $this->validate($rules, [
            'assignee_ids.required' => 'Phải gán ít nhất 1 nhân viên.',
            'site_address.required' => 'Địa chỉ thi công không được để trống.',
            'task_list.*.content.required' => 'Nội dung nhiệm vụ không được để trống.',
        ]);

        DB::beginTransaction();
        try {
            $customerId = $this->selected_customer_id;

            // 1. Tạo Khách mới (nếu có)
            if ($this->is_new_customer) {
                $customer = Customer::create(['name' => $this->new_customer_name]);
                CustomerContact::create(['customer_id' => $customer->id, 'type' => 'phone', 'value' => $this->new_customer_phone, 'is_primary' => true]);
                if ($this->new_customer_address) {
                    CustomerContact::create(['customer_id' => $customer->id, 'type' => 'address', 'value' => $this->new_customer_address, 'is_primary' => true]);
                }
                $customerId = $customer->id;
            }

            // 2. Tạo Work Order (Kèm thông tin thi công)
            $workOrder = WorkOrder::create([
                'customer_id' => $customerId,
                'created_by' => auth('admin')->id(), // Hoặc auth()->id() tùy guard
                'code' => 'WO-' . strtoupper(Str::random(6)),
                'title' => $this->title,
                'description' => $this->description,
                'status' => 'pending',
                'priority' => $this->priority,
                // Lưu thông tin thi công
                'site_address' => $this->site_address,
                'contact_person' => $this->contact_person,
                'contact_phone' => $this->contact_phone,
            ]);

            // 3. Gán nhân viên vào Work Order
            $workOrder->assignees()->attach($this->assignee_ids);

            // 4. Tạo các Task con (Nhiệm vụ cụ thể)
            // Logic: Tạo các task status 'pending'
            // Performer: Tạm thời gán cho người đầu tiên trong danh sách thợ được chọn (Leader)
            // Sau này thợ có thể tự chia lại hoặc Admin chia lại
            $mainPerformer = $this->assignee_ids[0]; 

            foreach ($this->task_list as $taskItem) {
                if(!empty($taskItem['content'])) {
                    Task::create([
                        'work_order_id' => $workOrder->id,
                        'performer_id' => $mainPerformer, // Gán tạm cho người đầu tiên
                        'report_content' => $taskItem['content'], // Tận dụng trường này làm tên task
                        // Có thể thêm cột 'title' vào bảng tasks nếu muốn tách biệt report và title
                        'collected_amount' => 0,
                        'is_paid' => false
                    ]);
                }
            }

            // 5. Gửi thông báo cho nhân viên được gán
            $assignees = Admin::whereIn('id', $this->assignee_ids)->get();
            \Illuminate\Support\Facades\Notification::send($assignees, new \App\Notifications\WorkOrderAssignedNotification($workOrder));

            DB::commit();

            $this->reset();
            $this->task_list = [['content' => '', 'note' => '']]; // Reset task list
            $this->dispatch('clear-select2'); 
            $this->reset(['priority']);
            session()->flash('success', 'Đã tạo phiếu việc thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}