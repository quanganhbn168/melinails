<div>
    <section class="content-header">
        <div class="container-fluid">
            <h1>{{ $staff_id ? 'Cập nhật Nhân viên' : 'Thêm Nhân viên Mới' }}</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- 1. Thêm autocomplete="off" vào thẻ form --}}
            <form wire:submit.prevent="save" autocomplete="off">
                
                {{-- 
                    === KỸ THUẬT CHẶN AUTO-FILL (QUAN TRỌNG) === 
                    Tạo 2 ô input "mồi" và đẩy nó ra khỏi màn hình. 
                    Trình duyệt sẽ tự động điền user/pass vào đây và bỏ qua các ô bên dưới.
                --}}
                <div style="position: absolute; top: -9999px; left: -9999px;">
                    <input type="text" name="fake_username_prevention" tabindex="-1">
                    <input type="password" name="fake_password_prevention" tabindex="-1">
                </div>
                {{-- =========================================== --}}

                <div class="row">
                    
                    {{-- Cột Trái: Thông tin đăng nhập --}}
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin tài khoản</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Họ và Tên <span class="text-danger">*</span></label>
                                    {{-- Thêm readonly onfocus... là một trick phụ để chặn trên mobile --}}
                                    <input type="text" wire:model="name" class="form-control" placeholder="VD: Nguyễn Văn Thợ" 
                                           autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Số điện thoại (Dùng để đăng nhập) <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="phone" class="form-control" placeholder="09xxxxxxxxx" 
                                           autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email (Tùy chọn)</label>
                                    <input type="email" wire:model="email" class="form-control" placeholder="email@example.com" 
                                           autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                {{-- XỬ LÝ MẬT KHẨU: Ẩn/Hiện + Random --}}
                                <div class="form-group" 
                                     x-data="{ 
                                         showPass: false,
                                         generatePass() {
                                             // Sinh pass 8 ký tự (chữ + số)
                                             let chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                                             let pass = '';
                                             for (let i = 0; i < 8; i++) {
                                                 pass += chars.charAt(Math.floor(Math.random() * chars.length));
                                             }
                                             // Đẩy giá trị vào biến password của Livewire
                                             $wire.set('password', pass);
                                             // Tự động hiện pass lên để nhìn
                                             this.showPass = true;
                                         }
                                     }">
                                    
                                    <label>Mật khẩu {!! $staff_id ? '<small class="text-muted font-weight-normal">(Để trống nếu không đổi)</small>' : '<span class="text-danger">*</span>' !!}</label>
                                    
                                    <div class="input-group">
                                        {{-- autocomplete="new-password" báo trình duyệt đây là pass mới, đừng điền pass cũ --}}
                                        <input :type="showPass ? 'text' : 'password'" 
                                               wire:model="password" 
                                               class="form-control" 
                                               placeholder="******"
                                               autocomplete="new-password"
                                               readonly onfocus="this.removeAttribute('readonly');">
                                        
                                        <div class="input-group-append">
                                            {{-- Nút Random --}}
                                            <button type="button" class="btn btn-default" @click="generatePass()" title="Tạo mật khẩu ngẫu nhiên">
                                                <i class="fas fa-key text-warning"></i>
                                            </button>
                                            {{-- Nút Xem Pass --}}
                                            <button type="button" class="btn btn-default" @click="showPass = !showPass" title="Xem/Ẩn mật khẩu">
                                                <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Cột Phải: Phân quyền & Trạng thái --}}
                    <div class="col-md-6">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Phân quyền & Trạng thái</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Vai trò (Chức vụ) <span class="text-danger">*</span></label>
                                    <div class="p-2 border rounded" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($roles as $r)
                                            <div class="custom-control custom-radio mb-2">
                                                {{-- Logic checked: Nếu đang tạo mới thì auto check 'staff', nếu sửa thì check role của user --}}
                                                <input class="custom-control-input" type="radio" 
                                                       id="role_{{ $r->id }}" 
                                                       name="role" 
                                                       value="{{ $r->name }}" 
                                                       wire:model="role"
                                                       {{ (!$staff_id && $r->name == 'staff') ? 'checked' : '' }}> 
                                                
                                                <label for="role_{{ $r->id }}" class="custom-control-label font-weight-normal" style="cursor: pointer;">
    
    {{-- HIỂN THỊ TÊN TIẾNG VIỆT TỪ TỪ ĐIỂN --}}
    <strong>
        {{ $roleLabels[$r->name] ?? ucfirst($r->name) }}
    </strong>
    
    <br>
    <small class="text-muted">
        {{-- Logic mô tả dựa trên KEY tiếng Anh --}}
        @if($r->name == 'super_admin') 
            Toàn quyền hệ thống.
        @elseif($r->name == 'staff') 
            Chỉ được xem việc được giao và báo cáo.
        @else
            Vai trò tùy chỉnh.
        @endif
    </small>
</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái hoạt động</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="statusSwitch" wire:model="status">
                                        <label class="custom-control-label" for="statusSwitch">
                                            {{ $status ? 'Đang hoạt động (Active)' : 'Đang bị khóa (Blocked)' }}
                                        </label>
                                    </div>
                                    <small class="text-muted">Nếu tắt, nhân viên này sẽ không thể đăng nhập.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center pb-4">
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary mr-2">Hủy bỏ</a>
                        <button type="submit" class="btn btn-success px-5">
                            <i class="fas fa-save"></i> LƯU THÔNG TIN
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>