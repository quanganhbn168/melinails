<?php

namespace App\Livewire\Warranty;

use Livewire\Component;
use App\Models\Product; // Assuming Product model has serial info or related table
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\TaskItem; // Assuming TaskItem holds sold products with serials
use Carbon\Carbon;

class WarrantyCheck extends Component
{
    public $activeTab = 'product'; // 'product' or 'service'
    
    // Product Warranty Search
    public $serialNumber = '';
    public $productResult = null;
    public $searchError = '';

    // Service Warranty Search
    public $customerSearch = '';
    public $serviceHistory = [];
    public $selectedJob = null;

    protected $queryString = ['activeTab'];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->reset(['serialNumber', 'productResult', 'searchError', 'customerSearch', 'serviceHistory', 'selectedJob']);
    }

    public function checkProductWarranty()
    {
        $this->reset(['productResult', 'searchError']);
        
        if (empty($this->serialNumber)) {
            $this->searchError = 'Vui lòng nhập hoặc quét số Serial.';
            return;
        }

        // 1. Tìm trong bảng WarrantyDevice (Chính xác nhất)
        $device = \App\Models\WarrantyDevice::where('serial_number', $this->serialNumber)->first();

        if ($device) {
            $this->productResult = [
                'name' => $device->device_name,
                'serial' => $device->serial_number,
                'sale_date' => $device->start_date ? $device->start_date->format('d/m/Y') : 'N/A',
                'expiry_date' => $device->expiration_date ? $device->expiration_date->format('d/m/Y') : 'N/A',
                'status' => $device->is_expired ? 'expired' : 'valid',
                'job_code' => $device->item->report->task->workOrder->code ?? 'N/A',
                'warranty_months' => $device->warranty_months,
            ];
        } else {
            // Fallback: Tìm trong TaskItem nếu chưa có bản ghi bảo hành chính thức (như logic cũ)
            // Hoặc chỉ báo không tìm thấy?
            // Theo yêu cầu "reuse or follow that same pattern", nên ưu tiên WarrantyDevice.
            // Nếu không thấy trong WarrantyDevice, có thể tìm trong TaskItem để xem có phải hàng mình bán không, nhưng chưa kích hoạt bảo hành?
            // Tạm thời giữ logic tìm TaskItem làm fallback nhưng hiển thị rõ là "Chưa kích hoạt bảo hành" hoặc tương tự.
            
            $item = TaskItem::where('serial_number', $this->serialNumber)->latest()->first();
            if ($item) {
                 // Logic cũ (tạm tính 12 tháng)
                $saleDate = $item->created_at;
                $warrantyMonths = 12; 
                $expiryDate = $saleDate->copy()->addMonths($warrantyMonths);
                $status = $expiryDate->isFuture() ? 'valid' : 'expired';

                $this->productResult = [
                    'name' => $item->item_name,
                    'serial' => $item->serial_number,
                    'sale_date' => $saleDate->format('d/m/Y'),
                    'expiry_date' => $expiryDate->format('d/m/Y') . ' (Dự kiến)',
                    'status' => $status,
                    'job_code' => $item->report->task->workOrder->code ?? 'N/A',
                    'warranty_months' => $warrantyMonths,
                    'note' => 'Chưa có dữ liệu bảo hành chính thức. Ngày tính theo ngày tạo phiếu.'
                ];
            } else {
                $this->searchError = 'Không tìm thấy thông tin bảo hành cho Serial này.';
            }
        }
    }

    public function searchServiceHistory()
    {
        $this->reset(['serviceHistory', 'selectedJob']);

        if (empty($this->customerSearch)) {
            return;
        }

        // Tìm khách hàng
        $customers = Customer::whereHas('contacts', function ($q) {
                $q->where('type', 'phone')
                  ->where('value', 'like', '%' . $this->customerSearch . '%');
            })
            ->orWhere('name', 'like', '%' . $this->customerSearch . '%')
            ->pluck('id');

        if ($customers->isEmpty()) {
            return;
        }

        // Lấy danh sách bảo hành dịch vụ (WarrantyService)
        // Và bảo hành thiết bị (WarrantyDevice) của khách này
        
        // 1. Warranty Services
        $services = \App\Models\WarrantyService::whereHas('workOrder', function($q) use ($customers) {
            $q->whereIn('customer_id', $customers);
        })->with('workOrder')->latest()->get();

        // 2. Warranty Devices
        $devices = \App\Models\WarrantyDevice::whereHas('item.report.task.workOrder', function($q) use ($customers) {
            $q->whereIn('customer_id', $customers);
        })->with('item.report.task.workOrder')->latest()->get();

        // Merge lại để hiển thị
        $this->serviceHistory = [
            'services' => $services,
            'devices' => $devices
        ];
    }

    public function selectJob($type, $id)
    {
        if ($type == 'service') {
            $this->selectedJob = \App\Models\WarrantyService::with('workOrder')->find($id);
            $this->selectedJob->type = 'service';
        } else {
            $this->selectedJob = \App\Models\WarrantyDevice::with('item.report.task.workOrder')->find($id);
            $this->selectedJob->type = 'device';
        }
    }

    public function render()
    {
        return view('livewire.warranty.warranty-check')
            ->layout('layouts.admin', ['title' => 'Tra cứu bảo hành']);
    }
}
