<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-tags"></i> Quản lý Tags</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button wire:click="openModal" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm Tag
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter theo Type --}}
            <div class="card">
                <div class="card-header">
                    <div class="btn-group">
                        @foreach($types as $tagType)
                            <button type="button" 
                                wire:click="$set('type', '{{ $tagType->value }}')" 
                                class="btn {{ $type == $tagType->value ? 'btn-primary' : 'btn-outline-secondary' }}">
                                <i class="{{ $tagType->icon() }}"></i> {{ $tagType->label() }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($tags as $tag)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge badge-lg" 
                                                style="background-color: {{ $tag->color }}; color: {{ $tag->text_color }}; font-size: 1rem;">
                                                {{ $tag->name }}
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                <button wire:click="openModal({{ $tag->id }})" class="btn btn-xs btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:confirm="Xóa tag này?" wire:click="delete({{ $tag->id }})" class="btn btn-xs btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if($tag->description)
                                            <p class="text-muted small mb-0 mt-2">{{ $tag->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class="fas fa-tags fa-3x mb-3"></i>
                                <p>Chưa có tag nào cho loại này</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Thêm/Sửa Tag --}}
    @if($showModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $editingId ? 'Sửa Tag' : 'Thêm Tag Mới' }}
                    </h5>
                    <button type="button" wire:click="closeModal" class="close">
                        <span>×</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên Tag <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control" placeholder="VD: Bảo hành">
                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Màu sắc <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <input type="color" wire:model.live="color" class="form-control form-control-color" style="width: 60px; height: 40px; padding: 2px;">
                                <input type="text" wire:model.live="color" class="form-control ml-2" style="width: 120px;" placeholder="#ffffff">
                                <span class="badge ml-3" style="background-color: {{ $color }}; color: {{ \App\Models\Tag::make(['color' => $color])->text_color }};">
                                    {{ $name ?: 'Preview' }}
                                </span>
                            </div>
                            @error('color') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            
                            {{-- Quick color palette --}}
                            <div class="mt-2 d-flex flex-wrap" style="gap: 5px;">
                                @foreach(['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14', '#e83e8c', '#20c997', '#6c757d'] as $c)
                                    <button type="button" wire:click="$set('color', '{{ $c }}')" 
                                        class="btn btn-sm p-0" 
                                        style="width: 28px; height: 28px; background-color: {{ $c }}; border: 2px solid {{ $color == $c ? '#000' : 'transparent' }}; border-radius: 4px;">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Mô tả</label>
                            <input type="text" wire:model="description" class="form-control" placeholder="Mô tả ngắn...">
                            @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
