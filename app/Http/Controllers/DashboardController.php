<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\Task;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // 1. Thống kê Card (Số liệu tổng quan)
        $totalJobs = WorkOrder::count();
        $processingJobs = WorkOrder::where('status', 'processing')->count();
        $totalCustomers = Customer::count();
        
        // Tính tổng doanh thu (chỉ tính từ các Task đã nộp tiền: is_paid = true)
        $totalRevenue = Task::where('is_paid', true)->sum('collected_amount');

        // 2. Bảng việc mới nhất (Lấy 5 cái mới tạo)
        $recentOrders = WorkOrder::with(['customer', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Dữ liệu Biểu đồ Doanh thu (6 tháng gần nhất)
        $revenueData = $this->getMonthlyRevenue();

        // 4. Dữ liệu Biểu đồ Trạng thái Job (Pie Chart)
        $statusData = [
            'pending' => WorkOrder::where('status', 'pending')->count(),
            'processing' => $processingJobs,
            'completed' => WorkOrder::where('status', 'completed')->count(),
            'cancelled' => WorkOrder::where('status', 'cancelled')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalJobs', 
            'processingJobs', 
            'totalCustomers', 
            'totalRevenue', 
            'recentOrders',
            'revenueData',
            'statusData'
        ));
    }

    // Hàm lấy doanh thu 6 tháng gần nhất
    private function getMonthlyRevenue()
    {
        // Khởi tạo mảng 6 tháng
        $labels = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            
            $labels[] = "T$month/$year";

            // Query tổng tiền theo tháng
            $amount = Task::where('is_paid', true)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('collected_amount');
            
            $data[] = $amount;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}