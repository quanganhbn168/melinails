<div>
    <form wire:submit.prevent="save">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Đã có lỗi xảy ra. Vui lòng kiểm tra lại các trường:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            {{-- CỘT TRÁI - THÔNG TIN CHÍNH --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <x-form.input name="name" label="Tên sản phẩm" wire:model.live.debounce.500ms="name" required />
                        <div class="mb-3">
                            @if ($slugStatus === 'valid')
                                <div class="form-text text-success" style="font-size: 0.875em;"><i class="fa fa-check-circle"></i> Đường dẫn tĩnh hợp lệ: <strong>{{ url('/') }}/san-pham/{{ $slug }}</strong></div>
                            @elseif ($slugStatus === 'invalid')
                                <div class="form-text text-danger" style="font-size: 0.875em;"><i class="fa fa-exclamation-circle"></i> Đường dẫn tĩnh này đã tồn tại.</div>
                            @endif
                        </div>
                        <div wire:ignore><x-form.ckeditor name="description" label="Mô tả" wire:model.defer="description" /></div>
                        <div wire:ignore><x-form.ckeditor name="content" label="Nội dung chi tiết" wire:model.defer="content" /></div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header"><div class="d-flex justify-content-between align-items-center"><h5 class="mb-0">Giá & Kho hàng</h5><x-form.switch name="hasVariants" label="Sản phẩm có biến thể" wire:model.live="hasVariants" /></div></div>
                    <div class="card-body">
                        @if ($hasVariants)
                            <div class="alert alert-info">Giao diện quản lý biến thể sẽ được xây dựng ở bước tiếp theo.</div>
                        @else
                            <div class="row">
                                <div class="col-md-6"><x-form.input name="sku" label="Mã sản phẩm (SKU)" wire:model.defer="sku" /></div>
                                <div class="col-md-6"><x-form.input name="stock" type="number" label="Số lượng tồn kho" wire:model.defer="stock" /></div>
                                <div class="col-md-6"><x-form.livewire.money-input name="price" label="Giá bán" :value="$price" wire:model.defer="price" /></div>
                                <div class="col-md-6"><x-form.livewire.money-input name="compare_at_price" label="Giá so sánh (giá cũ)" :value="$compare_at_price" wire:model.defer="compare_at_price" /></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header"><h5 class="mb-0">Hình ảnh sản phẩm</h5></div>
                    <div class="card-body">
                         <h5>Ảnh đại diện</h5>
                        <x-form.livewire.image-input name="image" label="" :image="$image" :value="$existingImage" wire:model="image" />
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI - THÔNG TIN PHỤ --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <x-form.switch name="status" label="Trạng thái (Hiển thị)" wire:model.defer="status" />
                        <hr>
                        <x-form.select
                            name="cate_type"
                            label="Loại sản phẩm"
                            placeholder="-- Chọn loại --"
                            :options="['physics' => 'Sản phẩm vật lý', 'services' => 'Dịch vụ']"
                            wire:model.live="cate_type"
                            required
                        />
                        <div>
                             <x-form.select
                                name="category_id"
                                id="category_id_select2"
                                label="Danh mục"
                                :options="$this->categoryTree"
                                wire:model="category_id"
                                required
                            />
                        </div>

                        <div wire:ignore>
                            <x-form.select
                                name="brand_id"
                                id="brand_id_select2"
                                label="Thương hiệu"
                                :options="$allBrands->pluck('name', 'id')"
                                wire:model="brand_id"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <span wire:loading.remove wire:target="save">Lưu sản phẩm</span>
                <span wire:loading wire:target="save">Đang lưu...</span>
            </button>
        </div>
    </form>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const initSelect2 = (selector, property) => {
                const el = $(selector);
                el.select2({ allowClear: true });
                el.on('change', (e) => { @this.set(property, e.target.value); });
            };
            
            initSelect2('#brand_id_select2', 'brand_id');

            // --- CKEditor không đổi ---
        });
    </script>
    @endpush
</div>