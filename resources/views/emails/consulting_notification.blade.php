<!DOCTYPE html>
<html>
<head>
    <title>Yêu cầu Tư vấn Triển khai Mới</title>
</head>
<body>
    <h2>Yêu cầu Tư vấn Triển khai từ Website</h2>
    <p><strong>Khách hàng:</strong> {{ $data->name }}</p>
    <p><strong>Công ty:</strong> {{ $data->company }}</p>
    <p><strong>Điện thoại:</strong> {{ $data->phone }}</p>
    <p><strong>Email:</strong> {{ $data->email }}</p>
    <p><strong>Địa chỉ:</strong> {{ $data->address }}</p>
    <p><strong>Ngân sách dự kiến:</strong> {{ $data->budget }}</p>
    <hr>
    <h3>Chi tiết yêu cầu:</h3>
    <p>{!! nl2br(e($data->details)) !!}</p>
    
    @if($data->file_path)
        <p><strong>File đính kèm:</strong> <a href="{{ asset('storage/' . $data->file_path) }}">Xem file</a></p>
    @endif
</body>
</html>
