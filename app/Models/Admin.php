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
        'name', 'email', 'password', 'phone', 'status', 'avatar'
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
     * Tất cả dùng layouts.admin
     * Mobile detection ở blade để hiện nav-bottom
     */
    public function getLayoutAttribute()
    {
        return 'layouts.admin';
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    }

    // --- MENTION NOTIFICATIONS ---

    /**
     * Lấy tất cả mentions chưa đọc của user này
     */
    public function unreadMentions()
    {
        return $this->hasMany(CommentMention::class, 'admin_id')
            ->where('is_read', false);
    }

    /**
     * Đếm số mentions chưa đọc (dùng cho badge)
     */
    public function getUnreadMentionsCountAttribute(): int
    {
        return $this->unreadMentions()->count();
    }

    /**
     * Lấy danh sách user có thể mention trong 1 WorkOrder
     * (Assignees + Admins/Mods)
     */
    public static function getMentionableForWorkOrder(WorkOrder $workOrder)
    {
        // Lấy assignees của phiếu
        $assigneeIds = $workOrder->assignees->pluck('id')->toArray();
        
        // Lấy tất cả admin có role admin hoặc super-admin
        $adminIds = self::role(['super-admin', 'admin'])->pluck('id')->toArray();
        
        // Merge và unique
        $ids = array_unique(array_merge($assigneeIds, $adminIds));
        
        return self::whereIn('id', $ids)->where('status', 1)->get();
    }
}
