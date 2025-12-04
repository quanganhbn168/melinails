<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Task;
use App\Models\TaskReport;
use App\Models\Admin; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard(\Illuminate\Http\Request $request)
    {
        // 1. THỐNG KÊ TỔNG QUAN (TOP CARDS)
        $totalProducts = \App\Models\Product::count();
        $totalPosts = \App\Models\Post::count();
        $totalApplications = \App\Models\CareerApplication::count();
        $totalContacts = \App\Models\Contact::count();

        // 2. BIỂU ĐỒ HOẠT ĐỘNG (Số lượng Task hoàn thành)
        // Xử lý lọc thời gian
        $range = $request->input('range', '7_days'); // Mặc định 7 ngày
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

    private function getActivityChartData($range, $customStart = null, $customEnd = null)
    {
        $labels = [];
        $data = [];
        
        // Xác định ngày bắt đầu và kết thúc
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6); // Mặc định 7 ngày

        if ($range == '28_days') {
            $startDate = Carbon::today()->subDays(27);
        } elseif ($range == '3_months') {
            $startDate = Carbon::today()->subMonths(3);
        } elseif ($range == 'custom' && $customStart && $customEnd) {
            $startDate = Carbon::parse($customStart);
            $endDate = Carbon::parse($customEnd);
        }

        // Tạo labels và data
        // Nếu khoảng thời gian quá dài (> 31 ngày), gom nhóm theo tuần hoặc tháng để biểu đồ đỡ rối?
        // Ở đây giữ nguyên theo ngày cho đơn giản, hoặc user tự chọn range ngắn lại.
        
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