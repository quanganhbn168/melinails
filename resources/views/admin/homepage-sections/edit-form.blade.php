{{-- Partial form for AJAX loading in Visual Builder --}}
<form id="sectionEditForm">
    @csrf
    
    {{-- Preview Image --}}
    @if($section->image || $section->background_image)
    <div class="form-section">
        <div class="form-section-title">Preview</div>
        <div class="preview-image-wrapper">
            <img src="{{ $section->image ?: $section->background_image }}" alt="{{ $section->name }}">
        </div>
    </div>
    @endif
    
    {{-- Thông tin cơ bản --}}
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-info-circle mr-1"></i> Thông tin cơ bản</div>
        
        <div class="form-group">
            <label for="title">Tiêu đề Section</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   class="form-control" 
                   value="{{ old('title', $section->title) }}"
                   placeholder="Nhập tiêu đề hiển thị trên trang chủ">
        </div>

        <div class="form-group">
            <label for="subtitle">Subtitle</label>
            <input type="text" 
                   name="subtitle" 
                   id="subtitle" 
                   class="form-control" 
                   value="{{ old('subtitle', $section->subtitle) }}"
                   placeholder="Nhập subtitle (nếu có)">
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" 
                      id="description" 
                      class="form-control" 
                      rows="3"
                      placeholder="Nhập mô tả ngắn gọn">{{ old('description', $section->description) }}</textarea>
        </div>
    </div>

    {{-- Hình ảnh --}}
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-image mr-1"></i> Hình ảnh</div>
        
        <x-form.image-picker 
            name="image" 
            label="Ảnh minh họa"
            :value="$section->image"
            placeholder="Chọn ảnh minh họa" />

        <x-form.image-picker 
            name="background_image" 
            label="Ảnh nền"
            :value="$section->background_image"
            placeholder="Chọn ảnh nền" />
    </div>

    {{-- Settings động theo loại section --}}
    @if(count($settingsFields) > 0)
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-cog mr-1"></i> Cấu hình bổ sung</div>
        
        @foreach($settingsFields as $field)
        <div class="form-group">
            <label for="settings_{{ $field['name'] }}">{{ $field['label'] }}</label>
            
            @if($field['type'] === 'textarea')
            <textarea name="settings[{{ $field['name'] }}]" 
                      id="settings_{{ $field['name'] }}" 
                      class="form-control" 
                      rows="2"
                      placeholder="{{ $field['placeholder'] ?? '' }}">{{ old('settings.' . $field['name'], $section->getSetting($field['name'])) }}</textarea>
            
            @elseif($field['type'] === 'image')
            <x-form.image-picker 
                :name="'settings[' . $field['name'] . ']'" 
                :label="''"
                :value="$section->getSetting($field['name'])"
                :placeholder="$field['placeholder'] ?? 'Chọn ảnh'" />
            
            @else
            <input type="text" 
                   name="settings[{{ $field['name'] }}]" 
                   id="settings_{{ $field['name'] }}" 
                   class="form-control" 
                   value="{{ old('settings.' . $field['name'], $section->getSetting($field['name'])) }}"
                   placeholder="{{ $field['placeholder'] ?? '' }}">
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Trạng thái --}}
    <div class="form-section">
        <div class="form-section-title"><i class="fas fa-toggle-on mr-1"></i> Trạng thái</div>
        
        <div class="custom-control custom-switch">
            <input type="checkbox" 
                   class="custom-control-input" 
                   id="is_active" 
                   name="is_active" 
                   value="1"
                   {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_active">
                Hiển thị section này trên trang chủ
            </label>
        </div>
    </div>
</form>


