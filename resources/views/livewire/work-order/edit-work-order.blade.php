<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-edit"></i> Cập nhật Phiếu: <b>{{ $code }}</b></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-default">Hủy bỏ</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form wire:submit.prevent="update">
                <div class="row">
                    {{-- CỘT TRÁI: THÔNG TIN LIÊN HỆ --}}
                    <div class="col-md-5">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">1. Thông tin địa điểm</h3>
                            </div>
                            <div class="card-body">
                                {{-- Khách hàng (Readonly) --}}
                                <div class="form-group">
                                    <label>Khách hàng (Không đổi)</label>
                                    <input type="text" class="form-control" value="{{ $customer_name }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Người phụ trách tại chỗ <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="contact_person" class="form-control">
                                    @error('contact_person') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>SĐT Liên hệ <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="contact_phone" class="form-control">
                                    @error('contact_phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Địa chỉ thi công <span class="text-danger">*</span></label>
                                    <textarea wire:model="site_address" class="form-control" rows="3"></textarea>
                                    @error('site_address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CỘT PHẢI: THÔNG TIN VIỆC --}}
                    <div class="col-md-7">
                        <div class="card card-secondary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">2. Thông tin công việc</h3>
                            </div>
                            <div class="card-body">
                                
                                {{-- Độ ưu tiên --}}
                                <div class="form-group">
                                    <label>Độ ưu tiên</label>
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary {{ $priority == 'low' ? 'active' : '' }}">
                                            <input type="radio" wire:model="priority" value="low"> Thấp
                                        </label>
                                        <label class="btn btn-outline-primary {{ $priority == 'medium' ? 'active' : '' }}">
                                            <input type="radio" wire:model="priority" value="medium"> Trung bình
                                        </label>
                                        <label class="btn btn-outline-warning {{ $priority == 'high' ? 'active' : '' }}">
                                            <input type="radio" wire:model="priority" value="high"> Cao
                                        </label>
                                        <label class="btn btn-outline-danger {{ $priority == 'urgent' ? 'active' : '' }}">
                                            <input type="radio" wire:model="priority" value="urgent"> Gấp
                                        </label>
                                    </div>
                                </div>

                                {{-- Tiêu đề --}}
                                <div class="form-group">
                                    <label>Tiêu đề yêu cầu <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="title" class="form-control">
                                    @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Nhân viên (Select2) --}}
                                <div class="form-group" wire:ignore>
                                    <label>Nhân viên thực hiện</label>
                                    <select id="staff-select" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Danh sách nhiệm vụ --}}
                                <div class="form-group">
                                    <label>Danh sách nhiệm vụ <span class="text-danger">*</span></label>
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr class="bg-light">
                                                <th class="text-center" style="width: 50px;">#</th>
                                                <th>Nội dung công việc</th>
                                                <th class="text-center" style="width: 100px;">Trạng thái</th>
                                                <th class="text-center" style="width: 50px;">Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tasks as $index => $task)
                                                @if(!$task['is_deleted'])
                                                    <tr wire:key="task-{{ $index }}">
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <input type="text" wire:model="tasks.{{ $index }}.content" class="form-control form-control-sm" placeholder="Nhập tên đầu việc...">
                                                            @error("tasks.$index.content") <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @if($task['status'] == 'completed')
                                                                <span class="badge badge-success">Đã xong</span>
                                                            @elseif($task['status'] == 'processing')
                                                                <span class="badge badge-primary">Đang làm</span>
                                                            @else
                                                                <span class="badge badge-warning">Chờ</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button type="button" wire:click="removeTask({{ $index }})" class="btn btn-xs btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="button" wire:click="addTask" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus"></i> Thêm đầu việc
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label>Mô tả chi tiết</label>
                                    <textarea wire:model="description" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> CẬP NHẬT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@push('js')
<script>
    document.addEventListener('livewire:initialized', () => {
        // 1. Khởi tạo Select2
        let select = $('#staff-select').select2({
            theme: 'bootstrap4',
            placeholder: 'Chọn nhân viên...'
        });

        // 2. Pre-select dữ liệu cũ (QUAN TRỌNG)
        // Lấy dữ liệu từ biến PHP $assignee_ids truyền vào
        let selectedValues = @json($assignee_ids);
        select.val(selectedValues).trigger('change');

        // 3. Lắng nghe sự kiện thay đổi để cập nhật lại Livewire
        select.on('change', function (e) {
            @this.set('assignee_ids', $(this).val());
        });
    });
</script>
@endpush