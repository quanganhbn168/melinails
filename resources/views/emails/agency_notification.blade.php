<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký Đại lý Mới</title>
</head>
<body>
    <h2>Yêu cầu Đăng ký Đại lý từ Website</h2>
    <p><strong>Họ tên:</strong> {{ $data->name }}</p>
    <p><strong>Số điện thoại:</strong> {{ $data->phone }}</p>
    <p><strong>Email:</strong> {{ $data->email }}</p>
    <p><strong>Cửa hàng:</strong> {{ $data->shop_name }}</p>
    <p><strong>Khu vực:</strong> {{ $data->area }}</p>
    <p><strong>Địa chỉ:</strong> {{ $data->address }}</p>
    <hr>
    <h3>Ghi chú / Kinh nghiệm:</h3>
    <p>{!! nl2br(e($data->details)) !!}</p>
</body>
</html>
