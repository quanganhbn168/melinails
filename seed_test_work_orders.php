<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\Task;
use App\Enums\WorkOrderStatus;
use App\Enums\WorkOrderPriority;
use App\Enums\TaskStatus;

// Lấy nhân viên Quang Anh (ID 8)
$staffId = 8;

// Lấy hoặc tạo customer
$customer = Customer::first() ?? Customer::create([
    'name' => 'Khách Test',
    'type' => 'individual',
]);

$statuses = [
    WorkOrderStatus::PENDING,
    WorkOrderStatus::PROCESSING,
    WorkOrderStatus::COMPLETED,
];

$priorities = [
    WorkOrderPriority::LOW,
    WorkOrderPriority::MEDIUM,
    WorkOrderPriority::HIGH,
    WorkOrderPriority::URGENT,
];

$titles = [
    'Lắp đặt camera an ninh',
    'Sửa chữa hệ thống mạng',
    'Bảo trì máy tính văn phòng',
    'Cài đặt phần mềm quản lý',
    'Kiểm tra hệ thống điện',
    'Lắp đặt điều hòa',
    'Sửa chữa máy in',
    'Cấu hình router wifi',
    'Lắp đặt hệ thống âm thanh',
    'Bảo trì server',
    'Nâng cấp RAM máy tính',
    'Thay ổ cứng SSD',
    'Lắp đặt màn hình LED',
    'Sửa chữa UPS',
    'Cài đặt hệ thống backup',
    'Lắp đặt access control',
    'Bảo trì CCTV',
    'Cấu hình VPN',
    'Lắp đặt đường truyền internet',
    'Sửa chữa điện thoại IP',
];

$addresses = [
    '123 Trần Duy Hưng, Cầu Giấy, Hà Nội',
    '45 Nguyễn Trãi, Thanh Xuân, Hà Nội',
    '78 Láng Hạ, Đống Đa, Hà Nội',
    '12 Hoàng Đạo Thúy, Thanh Xuân, Hà Nội',
    '89 Lê Văn Lương, Thanh Xuân, Hà Nội',
];

$descriptions = [
    'Khách hàng yêu cầu lắp đặt gấp trong ngày. Liên hệ trước khi đến.',
    'Hệ thống cũ cần thay thế hoàn toàn. Đã báo giá và được duyệt.',
    'Bảo trì định kỳ theo hợp đồng. Kiểm tra và vệ sinh thiết bị.',
    'Khách hàng VIP, cần hỗ trợ nhanh chóng và chu đáo.',
    null, // Some without description
];

echo "Tạo 20 Work Orders cho nhân viên ID $staffId...\n";

for ($i = 1; $i <= 20; $i++) {
    $status = $statuses[array_rand($statuses)];
    $priority = $priorities[array_rand($priorities)];
    
    // Random deadline: quá khứ, hôm nay, tương lai
    $deadlineOptions = [
        now()->subDays(rand(1, 3)), // Quá hạn
        now()->addHours(rand(1, 12)), // Hôm nay
        now()->addDays(rand(1, 7)), // Tương lai
    ];
    $deadline = $deadlineOptions[array_rand($deadlineOptions)];
    
    // Tạo Work Order
    $workOrder = WorkOrder::create([
        'customer_id' => $customer->id,
        'created_by' => 1,
        'title' => $titles[array_rand($titles)],
        'description' => $descriptions[array_rand($descriptions)],
        'status' => $status,
        'priority' => $priority,
        'deadline' => $deadline,
        'site_address' => $addresses[array_rand($addresses)],
        'contact_person' => 'Anh Minh ' . rand(1, 99),
        'contact_phone' => '09' . rand(10000000, 99999999),
    ]);
    
    // Gán cho nhân viên Quang Anh
    $workOrder->assignees()->attach($staffId);
    
    // Tạo 1-3 tasks
    $numTasks = rand(1, 3);
    for ($j = 1; $j <= $numTasks; $j++) {
        $taskStatus = match($status) {
            WorkOrderStatus::COMPLETED => TaskStatus::COMPLETED,
            WorkOrderStatus::PROCESSING => [TaskStatus::PENDING, TaskStatus::PROCESSING, TaskStatus::COMPLETED][array_rand([0,1,2])],
            default => TaskStatus::PENDING,
        };
        
        Task::create([
            'work_order_id' => $workOrder->id,
            'name' => "Công việc $j",
            'status' => $taskStatus,
            'performer_id' => $staffId,
        ]);
    }
    
    echo "✓ Created: {$workOrder->code} - {$workOrder->title} ({$status->value})\n";
}

echo "\n✅ Hoàn tất! Đã tạo 20 Work Orders.\n";
