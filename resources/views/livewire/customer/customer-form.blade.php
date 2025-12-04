<div>
    <section class="content-header">
        <div class="container-fluid">
            <h1>{{ $customer_id ? 'Cập nhật Hồ sơ Khách hàng' : 'Thêm Khách hàng Mới' }}</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form wire:submit.prevent="save">
                <div class="row">
                    {{-- Cột Trái: Thông tin chính --}}
                    <div class="col-md-7">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Thông tin chung</h3>
                            </div>
                            <div class="card-body">
                                
                                {{-- Loại khách --}}
                                <div class="form-group">
                                    <label class="d-block">Loại khách hàng</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="typePersonal" name="type" class="custom-control-input" value="personal" wire:model.live="type">
                                        <label class="custom-control-label" for="typePersonal">Cá nhân</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="typeCompany" name="type" class="custom-control-input" value="company" wire:model.live="type">
                                        <label class="custom-control-label" for="typeCompany">Doanh nghiệp / Tổ chức</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Tên Khách hàng / Công ty <span class="text-danger">*</span></label>
                                            <input type="text" wire:model="name" class="form-control font-weight-bold" placeholder="Nhập tên đầy đủ...">
                                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mã số thuế</label>
                                            <input type="text" wire:model="tax_code" class="form-control" placeholder="Mã số thuế...">
                                        </div>
                                    </div>
                                </div>

                                {{-- Nếu là Công ty thì hiện thêm Người đại diện --}}
                                @if($type == 'company')
                                    <div class="form-group">
                                        <label>Người đại diện pháp luật</label>
                                        <input type="text" wire:model="representative_name" class="form-control" placeholder="Tên giám đốc / Người đại diện...">
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Email chính (Nhận hóa đơn/Báo cáo)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                        <input type="email" wire:model="email" class="form-control" placeholder="email@example.com">
                                    </div>
                                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Ghi chú nội bộ</label>
                                    <textarea wire:model="notes" class="form-control" rows="3" placeholder="Ghi chú về tính cách, thói quen, công nợ..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cột Phải: Danh sách liên hệ mở rộng --}}
                    <div class="col-md-5">
                        <div class="card card-info card-outline">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-address-book mr-1"></i> Danh bạ liên hệ</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tbody>
                                        @foreach($contacts as $index => $contact)
                                            <tr>
                                                <td style="width: 40px" class="text-center align-middle">
                                                    @if($contact['type'] == 'phone')
                                                        <i class="fas fa-phone text-success" title="Điện thoại"></i>
                                                    @else
                                                        <i class="fas fa-map-marker-alt text-danger" title="Địa chỉ"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" wire:model="contacts.{{ $index }}.value" class="form-control form-control-sm font-weight-bold" 
                                                           placeholder="{{ $contact['type'] == 'phone' ? 'Số điện thoại...' : 'Địa chỉ chi tiết...' }}">
                                                    @error('contacts.'.$index.'.value') <span class="text-danger text-xs">Bắt buộc</span> @enderror
                                                </td>
                                                <td style="width: 120px">
                                                    <input type="text" wire:model="contacts.{{ $index }}.label" class="form-control form-control-sm" 
                                                           placeholder="Nhãn (VD: Kho, Kế toán...)">
                                                </td>
                                                <td style="width: 40px" class="text-center align-middle">
                                                    <button type="button" wire:click="removeContact({{ $index }})" class="btn btn-tool text-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <div class="p-3 text-center bg-light border-top">
                                    <small class="text-muted d-block mb-2">Thêm thông tin liên hệ khác:</small>
                                    <button type="button" wire:click="addContact('phone')" class="btn btn-sm btn-outline-success mr-2">
                                        <i class="fas fa-plus"></i> SĐT Phụ
                                    </button>
                                    <button type="button" wire:click="addContact('address')" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-plus"></i> Địa chỉ Phụ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer nút Lưu --}}
                <div class="row">
                    <div class="col-12 text-center pb-5 pt-3">
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-default mr-2">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5 shadow">
                            <i class="fas fa-save mr-1"></i> LƯU HỒ SƠ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>