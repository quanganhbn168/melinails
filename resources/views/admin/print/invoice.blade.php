<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Hóa đơn #{{ $workOrder->code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.5; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; text-transform: uppercase; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f8f9fa; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 50px; text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="no-print" style="margin-bottom: 20px;">
            <button onclick="window.print()">In Hóa Đơn</button>
            <button onclick="window.close()">Đóng</button>
        </div>

        <div class="header">
            <h1>HÓA ĐƠN THANH TOÁN</h1>
            <p>Mã phiếu: {{ $workOrder->code }}</p>
            <p>Ngày: {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="meta">
            <div>
                <strong>Khách hàng:</strong> {{ $workOrder->customer->name }}<br>
                <strong>Địa chỉ:</strong> {{ $workOrder->site_address }}<br>
                <strong>SĐT:</strong> {{ $workOrder->contact_phone }}
            </div>
            <div class="text-right">
                <strong>Đơn vị thực hiện:</strong> CNETPOS<br>
                <strong>Hotline:</strong> 0987.654.321
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên hàng hóa / Dịch vụ</th>
                    <th class="text-center">SL</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ number_format($item['price']) }}</td>
                    <td class="text-right">{{ number_format($item['total']) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalAmount) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Đã thanh toán:</strong></td>
                    <td class="text-right">{{ number_format($totalCollected) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Còn lại:</strong></td>
                    <td class="text-right"><strong>{{ number_format($balance) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Cảm ơn quý khách đã sử dụng dịch vụ!</p>
        </div>
    </div>
</body>
</html>
