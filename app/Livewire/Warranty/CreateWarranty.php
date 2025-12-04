<?php

namespace App\Livewire\Warranty;

use Livewire\Component;
use App\Models\WorkOrder;
use App\Models\TaskItem;
use App\Models\WarrantyDevice;
use App\Models\WarrantyService;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

class CreateWarranty extends Component
{
    public $workOrder;

    // --- 1. DỮ LIỆU BẢO HÀNH DỊCH VỤ (SERVICE) ---
    // Bổ sung thêm: warranty_months, expiration_date
    public $service = [
        'start_date' => '',
        'warranty_months' => 12, // Mặc định 12 tháng
        'expiration_date' => '',
        'total_amount' => 0,
        'notes' => '',
        'device_summary' => '', // (Có thể bỏ nếu không dùng trực tiếp)
    ];

    // --- 2. DỮ LIỆU BẢO HÀNH THIẾT BỊ (DEVICES) ---
    public $devices = []; 

    public function mount($work_order_id)
    {
        $this->workOrder = WorkOrder::with(['tasks.reports.items'])->findOrFail($work_order_id);
        
        $this->prepareServiceData();
        $this->prepareDeviceData();
    }

    public function prepareServiceData()
    {
        // 1. Lấy ngày báo cáo đầu tiên làm mốc
        $firstReport = DB::table('task_reports')
            ->join('tasks', 'task_reports.task_id', '=', 'tasks.id')
            ->where('tasks.work_order_id', $this->workOrder->id)
            ->orderBy('task_reports.created_at', 'asc')
            ->select('task_reports.created_at')
            ->first();

        $defaultStartDate = $firstReport 
            ? Carbon::parse($firstReport->created_at)->format('Y-m-d') 
            : date('Y-m-d');

        // 2. Tính tổng tiền từ tất cả các báo cáo
        $totalAmount = DB::table('task_reports')
            ->join('tasks', 'task_reports.task_id', '=', 'tasks.id')
            ->where('tasks.work_order_id', $this->workOrder->id)
            ->sum('task_reports.collected_amount'); 

        // 3. Load dữ liệu cũ hoặc khởi tạo mới
        $existingService = WarrantyService::where('work_order_id', $this->workOrder->id)->first();

        if ($existingService) {
            $this->service = [
                'start_date' => $existingService->start_date ? $existingService->start_date->format('Y-m-d') : $defaultStartDate,
                // Nếu DB chưa có cột này thì fallback về 12, nhưng nên migrate thêm cột
                'warranty_months' => $existingService->warranty_months ?? 12, 
                'expiration_date' => $existingService->expiration_date ? $existingService->expiration_date->format('Y-m-d') : '',
                'total_amount' => $totalAmount, // Luôn cập nhật lại tiền mới nhất từ báo cáo
                'notes' => $existingService->notes,
            ];
            
            // Nếu ngày hết hạn chưa có (do migration mới thêm), tính lại luôn
            if (empty($this->service['expiration_date']) && $this->service['start_date']) {
                 $this->service['expiration_date'] = Carbon::parse($this->service['start_date'])
                    ->addMonths((int)$this->service['warranty_months'])
                    ->format('Y-m-d');
            }

        } else {
            // Mới tinh
            $this->service = [
                'start_date' => $defaultStartDate,
                'warranty_months' => 12,
                'expiration_date' => Carbon::parse($defaultStartDate)->addMonths(12)->format('Y-m-d'),
                'total_amount' => $totalAmount,
                'notes' => '',
            ];
        }
    }

    public function prepareDeviceData()
    {
        $items = TaskItem::query()
            ->join('task_reports', 'task_items.task_report_id', '=', 'task_reports.id')
            ->join('tasks', 'task_reports.task_id', '=', 'tasks.id')
            ->where('tasks.work_order_id', $this->workOrder->id)
            ->select(
                'task_items.*', 
                'task_reports.created_at as sold_date',
                'tasks.report_content as task_name'
            )
            ->get();

        foreach ($items as $item) {
            $exists = WarrantyDevice::where('task_item_id', $item->id)->first();
            
            $soldDate = Carbon::parse($item->sold_date)->format('Y-m-d');
            $months = 12; 

            $this->devices[$item->id] = [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'serial_number' => $item->serial_number,
                'task_name' => $item->task_name,
                'is_existing' => $exists ? true : false,
                
                'selected' => $exists ? true : (!empty($item->serial_number)), 
                'sold_date' => $exists ? $exists->start_date->format('Y-m-d') : $soldDate,
                'warranty_months' => $exists ? $exists->warranty_months : $months,
                'expiration_date' => $exists 
                    ? $exists->expiration_date->format('Y-m-d') 
                    : Carbon::parse($soldDate)->addMonths($months)->format('Y-m-d'),
            ];
        }
    }

    // --- LOGIC TÍNH TOÁN NGÀY ---

    // 1. Xử lý thay đổi cho DỊCH VỤ (MỚI)
    // Hàm này được Livewire gọi tự động khi wire:model="service.xxx" thay đổi
    public function updatedService($value, $key)
    {
        // Nếu đổi ngày bắt đầu hoặc số tháng -> Tính lại ngày hết hạn
        if ($key === 'start_date' || $key === 'warranty_months') {
            if ($this->service['start_date'] && $this->service['warranty_months']) {
                $this->service['expiration_date'] = Carbon::parse($this->service['start_date'])
                    ->addMonths((int)$this->service['warranty_months'])
                    ->format('Y-m-d');
            }
        }
    }

    // 2. Xử lý thay đổi cho THIẾT BỊ
    public function updatedDevices($value, $key)
    {
        $parts = explode('.', $key);
        $id = $parts[0];
        $field = $parts[1];

        if ($field === 'sold_date' || $field === 'warranty_months') {
            $start = $this->devices[$id]['sold_date'];
            $months = (int) $this->devices[$id]['warranty_months'];
            
            if ($start && $months) {
                $this->devices[$id]['expiration_date'] = Carbon::parse($start)->addMonths($months)->format('Y-m-d');
            }
        }
    }

    public function save()
    {
        DB::transaction(function () {
            
            // --- 1. LƯU BẢO HÀNH DỊCH VỤ ---
            $listDetails = [];
            $listQty = [];

            foreach ($this->devices as $d) {
                $listQty[] = "{$d['item_name']}"; 
                if ($d['selected']) {
                    $listDetails[] = "{$d['item_name']} - SN: {$d['serial_number']} - {$d['warranty_months']} tháng";
                }
            }

            WarrantyService::updateOrCreate(
                ['work_order_id' => $this->workOrder->id],
                [
                    'customer_name' => $this->workOrder->customer->name,
                    'total_amount' => $this->service['total_amount'],
                    'start_date' => $this->service['start_date'],
                    
                    // LƯU THÊM 2 TRƯỜNG NÀY
                    'warranty_months' => $this->service['warranty_months'],
                    'expiration_date' => $this->service['expiration_date'],

                    'device_list_details' => implode("\n", $listDetails),
                    'device_list_qty' => implode("\n", $listQty),
                    'notes' => $this->service['notes']
                ]
            );

            // --- 2. LƯU BẢO HÀNH THIẾT BỊ ---
            foreach ($this->devices as $itemId => $data) {
                if ($data['selected']) {
                    WarrantyDevice::updateOrCreate(
                        ['task_item_id' => $itemId],
                        [
                            'device_name' => $data['item_name'],
                            'serial_number' => $data['serial_number'],
                            'start_date' => $data['sold_date'],
                            'warranty_months' => $data['warranty_months'],
                            'expiration_date' => $data['expiration_date'],
                            'status' => 'active',
                        ]
                    );
                } else {
                    WarrantyDevice::where('task_item_id', $itemId)->delete();
                }
            }
        });

        session()->flash('success', 'Đã kích hoạt Bảo hành Dịch vụ & Thiết bị!');
        return redirect()->route('admin.work-orders.index');
    }

    public function render()
    {
        return view('livewire.warranty.create-warranty')
            ->layout(auth('admin')->user()->layout);
    }
}