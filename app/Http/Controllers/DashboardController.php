<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Task;
use App\Models\TaskReport;
use App\Models\Admin; 
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard(\Illuminate\Http\Request $request)
    {
        $user = auth('admin')->user();

        // Route đến dashboard phù hợp theo role
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return $this->adminDashboard($request);
        }
        
        if ($user->hasRole('staff')) {
            return $this->staffDashboard($request);
        }

        // Default cho các role khác
        return $this->defaultDashboard($request);
    }

    /**
     * DASHBOARD CHO SUPER ADMIN / ADMIN
     * Giữ nguyên logic cũ
     */
    protected function adminDashboard(\Illuminate\Http\Request $request)
    {
        // 1. THỐNG KÊ TỔNG QUAN (TOP CARDS)
        $totalProducts = \App\Models\Product::count();
        $totalPosts = \App\Models\Post::count();
        $totalApplications = \App\Models\CareerApplication::count();
        $totalContacts = \App\Models\Contact::count();

        // 2. BIỂU ĐỒ HOẠT ĐỘNG (Số lượng Task hoàn thành)
        $range = $request->input('range', '7_days');
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        $activityChart = $this->getActivityChartData($range, $customStart, $customEnd);

        // 3. BIỂU ĐỒ TRẠNG THÁI TASK (PIE CHART)
        $taskStatusData = [
            'pending' => Task::where('status', \App\Enums\TaskStatus::PENDING)->count(),
            'processing' => Task::where('status', \App\Enums\TaskStatus::PROCESSING)->count(),
            'completed' => Task::where('status', \App\Enums\TaskStatus::COMPLETED)->count(),
        ];

        // 4. DANH SÁCH PHIẾU VIỆC GẦN ĐÂY
        $recentWorkOrders = WorkOrder::with(['customer'])
            ->latest()
            ->take(5)
            ->get();

        // 5. TOP KỸ THUẬT VIÊN (Tháng này)
        $topTechnicians = Task::select('performer_id', DB::raw('count(*) as total'))
            ->where('status', \App\Enums\TaskStatus::COMPLETED)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereNotNull('performer_id')
            ->groupBy('performer_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('performer')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalPosts',
            'totalApplications',
            'totalContacts',
            'activityChart',
            'taskStatusData',
            'recentWorkOrders',
            'topTechnicians',
            'range',
            'customStart',
            'customEnd'
        ));
    }

    /**
     * DASHBOARD CHO STAFF (Nhân viên thi công)
     * Tập trung vào công việc cá nhân
     */
    protected function staffDashboard(\Illuminate\Http\Request $request)
    {
        $userId = auth('admin')->id();
        $now = Carbon::now();

        // Stats cards
        $myPendingTasks = Task::where('performer_id', $userId)
            ->where('status', \App\Enums\TaskStatus::PENDING)
            ->count();

        $myProcessingTasks = Task::where('performer_id', $userId)
            ->where('status', \App\Enums\TaskStatus::PROCESSING)
            ->count();

        $myCompletedTasks = Task::where('performer_id', $userId)
            ->where('status', \App\Enums\TaskStatus::COMPLETED)
            ->whereMonth('updated_at', $now->month)
            ->count();

        // Tiền đã thu tháng này
        $myCollectedAmount = TaskReport::whereHas('task', function ($q) use ($userId) {
                $q->where('performer_id', $userId);
            })
            ->whereMonth('created_at', $now->month)
            ->sum('collected_amount');

        // Công việc hôm nay (task được giao có deadline hôm nay hoặc đang làm dở)
        $todayTasks = Task::where('performer_id', $userId)
            ->whereIn('status', [\App\Enums\TaskStatus::PENDING, \App\Enums\TaskStatus::PROCESSING])
            ->whereHas('workOrder', function ($q) {
                $q->whereDate('deadline', '>=', Carbon::today()->subDays(1))
                  ->orWhereNull('deadline');
            })
            ->with('workOrder')
            ->orderByRaw("FIELD(status, 'processing', 'pending')")
            ->take(10)
            ->get();

        // Phiếu việc gần đây của tôi
        $myRecentWorkOrders = WorkOrder::whereHas('tasks', function ($q) use ($userId) {
                $q->where('performer_id', $userId);
            })
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard.staff', compact(
            'myPendingTasks',
            'myProcessingTasks',
            'myCompletedTasks',
            'myCollectedAmount',
            'todayTasks',
            'myRecentWorkOrders'
        ));
    }

    /**
     * DASHBOARD MẶC ĐỊNH (Cho các role khác: sales, warehouse, content, cs...)
     */
    protected function defaultDashboard(\Illuminate\Http\Request $request)
    {
        $user = auth('admin')->user();

        $data = [];

        // Chỉ load data user có quyền xem
        if ($user->can('view_work_orders')) {
            $data['totalWorkOrders'] = WorkOrder::count();
        }

        if ($user->can('view_customers')) {
            $data['totalCustomers'] = Customer::count();
        }

        if ($user->can('view_materials')) {
            // Nếu có model Material
            $data['totalMaterials'] = 0; // TODO: Thêm Material::count() khi có model
        }

        return view('admin.dashboard.default', $data);
    }

    private function getActivityChartData($range, $customStart = null, $customEnd = null)
    {
        $labels = [];
        $data = [];
        
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        if ($range == '28_days') {
            $startDate = Carbon::today()->subDays(27);
        } elseif ($range == '3_months') {
            $startDate = Carbon::today()->subMonths(3);
        } elseif ($range == 'custom' && $customStart && $customEnd) {
            $startDate = Carbon::parse($customStart);
            $endDate = Carbon::parse($customEnd);
        }

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $labels[] = $current->format('d/m');
            
            $count = Task::where('status', \App\Enums\TaskStatus::COMPLETED)
                ->whereDate('updated_at', $current)
                ->count();
            
            $data[] = $count;
            
            $current->addDay();
        }

        return ['labels' => $labels, 'data' => $data];
    }
}