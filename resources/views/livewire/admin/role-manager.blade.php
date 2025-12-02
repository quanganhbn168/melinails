<div>
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-user-shield"></i> Quản lý Vai trò</h1>
                <button wire:click="create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Mới
                </button>
            </div>
        </div>
    </section>

    {{-- DANH SÁCH ROLES --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($roles as $role)
                    <div class="col-md-4">
                        <div class="card h-100 card-outline {{ $role->name == 'super_admin' ? 'card-danger' : 'card-info' }}">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold text-uppercase">
                                    {{-- Hiển thị tên đẹp (thay thế _ bằng khoảng trắng) --}}
                                    {{ str_replace('_', ' ', $role->name) }}
                                </h3>
                                <div class="card-tools">
                                    @if($role->name !== 'super_admin')
                                        <button wire:click="edit({{ $role->id }})" class="btn btn-tool text-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($role->name !== 'staff')
                                            <button wire:confirm="Bạn có chắc muốn xóa?" wire:click="delete({{ $role->id }})" class="btn btn-tool text-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @else
                                        <span class="badge badge-danger">System</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if($role->name == 'super_admin')
                                    <p class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Toàn quyền hệ thống</p>
                                @else
                                    <div style="height: 150px; overflow-y: auto;">
                                        @forelse($role->permissions as $p)
                                            <span class="badge badge-secondary mb-1">{{ $p->name }}</span>
                                        @empty
                                            <span class="text-muted small">Chưa cấp quyền nào</span>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MODAL FORM (MATRIX STYLE) --}}
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Phân quyền chi tiết</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                
                <div class="modal-body p-0">
                    <form wire:submit.prevent="save">
                        
                        <div class="p-3 bg-light border-bottom">
                            <div class="form-group mb-0">
                                <label>Tên Vai Trò (Ví dụ: Kế toán trưởng)</label>
                                <input type="text" wire:model="name" class="form-control" placeholder="Nhập tên..." {{ $isEditing && in_array($name, ['super_admin', 'staff']) ? 'disabled' : '' }}>
                            </div>
                        </div>

                        {{-- BẢNG MATRIX --}}
                        <div class="table-responsive" style="max-height: 60vh;">
                            <table class="table table-hover table-bordered table-striped mb-0">
                                <thead class="bg-white" style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width: 250px;">Chức năng (Module)</th>
                                        <th class="text-center text-success">Xem</th>
                                        <th class="text-center text-primary">Thêm</th>
                                        <th class="text-center text-warning">Sửa</th>
                                        <th class="text-center text-danger">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $moduleKey => $moduleLabel)
                                        <tr>
                                            <td class="font-weight-bold align-middle">
                                                {{ $moduleLabel }}
                                                <br>
                                                <small class="text-muted font-weight-normal">{{ $moduleKey }}</small>
                                            </td>
                                            
                                            {{-- Loop qua 4 hành động: view, create, update, delete --}}
                                            @foreach(['view', 'create', 'update', 'delete'] as $act)
                                                @php 
                                                    $permName = "{$act}_{$moduleKey}"; 
                                                @endphp
                                                <td class="text-center align-middle">
                                                    <div class="custom-control custom-checkbox" style="transform: scale(1.2);">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="chk_{{ $permName }}" 
                                                               value="{{ $permName }}" 
                                                               wire:model="selectedPermissions">
                                                        <label class="custom-control-label" for="chk_{{ $permName }}"></label>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" wire:click="save" class="btn btn-primary px-4 font-weight-bold">
                        <i class="fas fa-save"></i> LƯU PHÂN QUYỀN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('open-role-modal', () => { $('#roleModal').modal('show'); });
        @this.on('close-role-modal', () => { $('#roleModal').modal('hide'); });
    });
</script>
@endpush