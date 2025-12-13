# Tổng hợp Công việc - 13/12/2024

## ✅ ĐÃ HOÀN THÀNH

### 1. Multi-level Menu Vật tư
- Gộp 3 menu riêng lẻ thành 1 menu đa cấp "Quản lý Vật tư"
- File: `config/menu.php`

### 2. Returned Items Tracking (Vật tư thu hồi)
- Thêm các trường: `supplier_id`, `status`, `returned_by`, `returned_at`, `notes`
- Các trạng thái: Chờ xử lý → Gửi NCC → Đã mang về → Đóng
- Cho phép thay đổi status nhanh bằng dropdown
- Gán nhà cung cấp + thêm ghi chú
- Files: 
  - `database/migrations/2025_12_13_220000_add_tracking_to_returned_items.php`
  - `app/Models/ReturnedItem.php`
  - `app/Livewire/Material/ReturnedMaterialList.php`
  - `resources/views/livewire/material/returned-material-list.blade.php`

### 3. Staff Performance
- Trang hiệu suất nhân viên: `/admin/staff/{id}/performance`
- Stats: Tasks giao, hoàn thành, tỷ lệ %, tiền thu hộ
- Phân tích theo Work Order tags
- Filter theo tháng/năm
- Thêm nút vào danh sách nhân viên
- Files:
  - `app/Livewire/Admin/StaffPerformance.php`
  - `resources/views/livewire/admin/staff-performance.blade.php`
  - `routes/admin.php`

### 4. Edit Work Order - Attachments
- Hiển thị file đính kèm cũ khi Edit
- Cho phép xem/xóa file cũ
- Upload file mới với preview
- Files:
  - `app/Livewire/WorkOrder/EditWorkOrder.php`
  - `resources/views/livewire/work-order/edit-work-order.blade.php`

### 5. Thiết bị thu hồi - Cải tiến
- Autocomplete tên từ danh sách vật tư
- Nút quét mã QR cho Serial
- Nút "Quét liên tục" cho thiết bị thu hồi
- Files:
  - `app/Livewire/WorkOrder/TaskDetail.php`
  - `resources/views/livewire/work-order/task-detail/tab-report.blade.php`
  - `resources/views/livewire/work-order/task-detail/scripts.blade.php`

### 6. Form Tạo công việc tiếp theo
- Thêm trường "Mô tả chi tiết"
- Highlight [QUAN TÂM] cho phần "Gán cho"
- Thông báo cho Admin nếu chưa gán người
- Files:
  - `resources/views/livewire/work-order/task-detail/tab-report.blade.php`
  - `resources/views/livewire/work-order/task-detail/css.blade.php`

### 7. Fix Bugs
- Fix spam toast khi quét mã liên tục (cooldown 2s)
- Fix scanner tắt sau 1 lần quét (wire:ignore cho Livewire)
- Disable GitHub Actions workflow (deploy.yml.disabled)

---

## ⏳ CÒN LẠI (Phase 2+)

### Phase 2: Module Khách hàng
- [ ] Tab "Doanh thu" theo khách hàng
- [ ] Tab "Lịch sử dịch vụ" timeline Work Orders
- [ ] Tab "Hàng hóa đã mua"
- [ ] Theo dõi hàng nhận về (BH, sửa chữa, nâng cấp)

### Phase 3: Hàng hóa & Dịch vụ
- [ ] Kích hoạt sản phẩm theo Serial + Ngày BH
- [ ] Tổng hợp lịch sử sử dụng dịch vụ
- [ ] Tạo QR tra cứu sản phẩm

### Phase 4: Quản lý Bảo hành
- [ ] Luồng trạng thái: Nhận KH → Gửi NCC → NCC trả → Trả KH
- [ ] Sổ theo dõi bảo hành
- [ ] Form tra cứu Serial
- [ ] Phân quyền riêng

### Phase 5: Giao diện & Dashboard
- [ ] Dashboard mặc định khi đăng nhập
- [ ] SOS Việc ưu tiên (khẩn cấp, deadline gấp)
- [ ] List công việc quá hạn
- [ ] Menu "Check bảo hành"

### Cải tiến thêm
- [ ] Upload hình ảnh cho task mới (spawn task)
- [ ] Admin xem và điều chuyển task chưa gán

---

## 📦 Commits hôm nay
1. `fa7f397` - Disable GitHub Actions
2. `2565003` - Multi-level menu, returned item tracking, staff performance
3. `4d98abb` - Fix attachments EditWorkOrder
4. `6058a57` - Phase 1 improvements
5. `581d6c3` - Fix toast spam
6. `632f5ac` - Fix scanner wire:ignore
