<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý Kho Vật Tư</h3>
        </div>
        <div class="card-body">
            {{-- Form nhập nhanh --}}
            <form wire:submit.prevent="save" class="row align-items-end mb-4 bg-light p-3 rounded">
                <div class="col-md-2">
                    <label>Mã SKU</label>
                    <input type="text" wire:model="code" class="form-control form-control-sm" placeholder="VD: CAM-01">
                </div>
                <div class="col-md-3">
                    <label>Tên vật tư <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="form-control form-control-sm" placeholder="Tên đầy đủ...">
                </div>
                <div class="col-md-3">
                    <label>Tìm kiếm tắt (Short Name)</label>
                    <input type="text" wire:model="short_name" class="form-control form-control-sm" placeholder="VD: cam hik, day mang...">
                    <small class="text-muted">Gõ từ này sẽ tìm ra vật tư này</small>
                </div>
                <div class="col-md-1">
                    <label>ĐVT</label>
                    <input type="text" wire:model="unit" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label>Đơn giá</label>
                    <input type="number" wire:model="price" class="form-control form-control-sm">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-primary w-100">Lưu</button>
                </div>
            </form>

            {{-- Danh sách --}}
            <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Tìm kiếm vật tư...">
            
            <table class="table table-bordered table-hover">
                <thead class="bg-secondary">
                    <tr>
                        <th>Mã</th>
                        <th>Tên vật tư</th>
                        <th>Từ khóa tìm kiếm (Viết tắt)</th>
                        <th>ĐVT</th>
                        <th>Giá</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $m)
                        <tr>
                            <td>{{ $m->code }}</td>
                            <td class="font-weight-bold">{{ $m->name }}</td>
                            <td class="text-info">{{ $m->short_name }}</td>
                            <td>{{ $m->unit }}</td>
                            <td>{{ number_format($m->price) }}</td>
                            <td>
                                <button wire:click="edit({{ $m->id }})" class="btn btn-xs btn-warning">Sửa</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $materials->links() }}
        </div>
    </div>
</div>