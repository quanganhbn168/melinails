<?php

namespace App\Livewire\Warranty;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WarrantyDevice;
use Livewire\Attributes\Layout;

class WarrantyList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Bộ lọc
    public $type = 'device'; // device, service
    public $status = 'all'; // all, active, expiring_soon, expired
    public $search = ''; // Tên máy, Serial, Mã Job
    public $customerName = '';
    public $customerPhone = '';
    public $dateType = 'expiration_date'; // start_date, expiration_date
    public $dateFrom = '';
    public $dateTo = '';
    
    public $showFilters = true; // Toggle filter panel

    public function updatingType() { $this->resetPage(); $this->resetFilters(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->status = 'all';
        $this->search = '';
        $this->customerName = '';
        $this->customerPhone = '';
        $this->dateFrom = '';
        $this->dateTo = '';
    }

    public function setQuickFilter($filterType)
    {
        $this->resetFilters();
        switch ($filterType) {
            case 'expiring_30':
                $this->status = 'expiring_soon';
                break;
            case 'expired':
                $this->status = 'expired';
                break;
            case 'created_7':
                $this->dateType = 'start_date';
                $this->dateFrom = now()->subDays(7)->format('Y-m-d');
                break;
        }
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        if ($this->type == 'service') {
            $query = \App\Models\WarrantyService::query()
                ->with(['workOrder.customer']);
        } else {
            $query = WarrantyDevice::query()
                ->with(['item.report.task.workOrder.customer']);
        }

        // 1. Filter by Status
        if ($this->status != 'all') {
            if ($this->status == 'active') {
                $query->where('expiration_date', '>=', now());
            } elseif ($this->status == 'expired') {
                $query->where('expiration_date', '<', now());
            } elseif ($this->status == 'expiring_soon') {
                $query->where('expiration_date', '>=', now())
                      ->where('expiration_date', '<=', now()->addDays(30));
            }
        }

        // 2. Filter by Search (Product Name, Serial, Job Code)
        if (!empty($this->search)) {
            $term = $this->search;
            if ($this->type == 'device') {
                $query->where(function($q) use ($term) {
                    $q->where('device_name', 'like', "%$term%")
                      ->orWhere('serial_number', 'like', "%$term%")
                      ->orWhereHas('item.report.task.workOrder', function($sq) use ($term) {
                          $sq->where('code', 'like', "%$term%");
                      });
                });
            } else {
                $query->where(function($q) use ($term) {
                    $q->where('device_list_details', 'like', "%$term%")
                      ->orWhereHas('workOrder', function($sq) use ($term) {
                          $sq->where('code', 'like', "%$term%")
                            ->orWhere('title', 'like', "%$term%");
                      });
                });
            }
        }

        // 3. Filter by Customer Name
        if (!empty($this->customerName)) {
            $term = $this->customerName;
            if ($this->type == 'device') {
                $query->whereHas('item.report.task.workOrder.customer', function($q) use ($term) {
                    $q->where('name', 'like', "%$term%");
                });
            } else {
                $query->whereHas('workOrder.customer', function($q) use ($term) {
                    $q->where('name', 'like', "%$term%");
                });
            }
        }

        // 4. Filter by Customer Phone
        if (!empty($this->customerPhone)) {
            $term = $this->customerPhone;
            // Helper closure for phone search
            $phoneQuery = function($q) use ($term) {
                $q->whereHas('contacts', function($sq) use ($term) {
                    $sq->where('type', 'phone')->where('value', 'like', "%$term%");
                });
            };

            if ($this->type == 'device') {
                $query->whereHas('item.report.task.workOrder.customer', $phoneQuery);
            } else {
                $query->whereHas('workOrder.customer', $phoneQuery);
            }
        }

        // 5. Filter by Date
        if (!empty($this->dateFrom)) {
            $query->whereDate($this->dateType, '>=', $this->dateFrom);
        }
        if (!empty($this->dateTo)) {
            $query->whereDate($this->dateType, '<=', $this->dateTo);
        }

        $query->orderBy('id', 'desc');

        return view('livewire.warranty.warranty-list', [
            'warranties' => $query->paginate(10)
        ]);
    }
}