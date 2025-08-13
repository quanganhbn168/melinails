<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'code',
        'status',
        'payment_method',
        'note',
        'total_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
    ];
    
    /**
     * Ghi đè phương thức boot của model.
     * Tự động thực thi khi model được khởi tạo.
     */
    protected static function boot()
    {
        parent::boot();

        // Lắng nghe sự kiện 'creating' - sự kiện này xảy ra ngay trước khi
        // một bản ghi mới được lưu vào database.
        static::creating(function ($model) {
            // 1. Tạo tiền tố theo định dạng TP-NĂMNĂMTHÁNGTHÁNGNGÀYNGÀY-
            // Ví dụ: TP-250811-
            $prefix = 'TP-' . date('ymd') . '-';

            // 2. Tìm đơn hàng cuối cùng trong ngày hôm nay để lấy số thứ tự
            $lastOrderToday = self::where('code', 'LIKE', $prefix . '%')->latest('id')->first();

            // 3. Tính toán số thứ tự tiếp theo
            if ($lastOrderToday) {
                // Nếu đã có đơn hàng trong ngày, lấy số cuối cùng và +1
                $lastNumber = (int) substr($lastOrderToday->code, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                // Nếu đây là đơn hàng đầu tiên trong ngày
                $nextNumber = 1;
            }

            // 4. Tạo mã cuối cùng với 4 chữ số (ví dụ: 0001, 0002, 0015)
            // và gán vào thuộc tính 'code' của model trước khi lưu
            $model->code = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
    /**
     * Lấy thông tin khách hàng (nếu khách là thành viên).
     */
    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Lấy tất cả các chi tiết trong đơn hàng này.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class);
    }

}