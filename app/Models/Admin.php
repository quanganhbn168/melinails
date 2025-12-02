<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'admin';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function assignedWorkOrders()
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_assignees', 'admin_id', 'work_order_id');
    }

    // Các Task đã làm
    public function performedTasks()
    {
        return $this->hasMany(Task::class, 'performer_id');
    }

    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * Xác định layout phù hợp cho user này.
     * Logic: 
     * - Nếu là Staff -> Ưu tiên Mobile (hoặc check device nếu muốn xịn hơn)
     * - Nếu là Super Admin -> Admin Panel
     */
    public function getLayoutAttribute()
    {
        // 1. Kiểm tra Role
        if ($this->hasRole('staff')) { // Hoặc check permission 'view-mobile-layout'
            return 'layouts.mobile';
        }

        // 2. Mặc định cho các role khác (Super Admin, Kế toán...)
        return 'layouts.admin';
    }
}
