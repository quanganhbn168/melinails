<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;

class WorkOrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'task_report_id',
        'payment_type',
        'amount',
        'description',
        'is_collected',
        'payment_method',
        'transfer_target',
        'status',
        'created_by',
        'collector_id',
        'collected_at',
        'verified_by',
        'verified_at',
        'note',
    ];

    protected $casts = [
        'payment_type' => PaymentType::class,
        'status' => PaymentStatus::class,
        'is_collected' => 'boolean',
        'amount' => 'integer',
        'collected_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // --- RELATIONSHIPS ---

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function taskReport()
    {
        return $this->belongsTo(TaskReport::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function collector()
    {
        return $this->belongsTo(Admin::class, 'collector_id');
    }

    public function verifier()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    // --- SCOPES ---

    public function scopePending($query)
    {
        return $query->where('status', PaymentStatus::PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', PaymentStatus::VERIFIED);
    }

    public function scopeCollected($query)
    {
        return $query->where('is_collected', true);
    }

    public function scopeUncollected($query)
    {
        return $query->where('is_collected', false);
    }

    // --- HELPERS ---

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount) . 'đ';
    }

    public function verify($adminId)
    {
        $this->update([
            'status' => PaymentStatus::VERIFIED,
            'verified_by' => $adminId,
            'verified_at' => now(),
        ]);
    }

    public function markAsCollected($adminId, $method = 'cash', $target = null)
    {
        $this->update([
            'is_collected' => true,
            'collector_id' => $adminId,
            'collected_at' => now(),
            'payment_method' => $method,
            'transfer_target' => $target,
        ]);
    }
}
