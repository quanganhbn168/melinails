@extends('layouts.admin')

@section('title', 'Quản lý nội dung Trang chủ')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-home mr-2"></i>Quản lý nội dung Trang chủ</h1>
    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary">
        <i class="fas fa-external-link-alt mr-1"></i> Xem trang chủ
    </a>
</div>
@stop

@section('content')
<div class="row">
    {{-- Sidebar: Module List --}}
    <div class="col-md-4 col-lg-3">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-layer-group mr-2"></i>Danh sách Module</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @php
                        $iconMap = [
                            'hero' => 'fa-images',
                            'intro' => 'fa-info-circle',
                            'fields' => 'fa-th-large',
                            'projects' => 'fa-project-diagram',
                            'partners' => 'fa-handshake',
                            'core_values' => 'fa-ad',
                            'news' => 'fa-newspaper',
                            'careers' => 'fa-briefcase',
                            'testimonials' => 'fa-comments',
                            'contact_form' => 'fa-envelope',
                        ];
                    @endphp
                    @foreach($sections as $key => $section)
                    <a href="javascript:void(0)" 
                       class="list-group-item list-group-item-action d-flex align-items-center module-item {{ !$section->is_active ? 'text-muted' : '' }}" 
                       data-id="{{ $section->id }}"
                       data-name="{{ $section->name }}">
                        <span class="mr-3 text-primary">
                            <i class="fas {{ $iconMap[$section->key] ?? 'fa-puzzle-piece' }}"></i>
                        </span>
                        <span class="flex-grow-1">
                            <strong>{{ $section->name }}</strong>
                            <small class="d-block text-muted">{{ $section->key }}</small>
                        </span>
                        <div class="custom-control custom-switch ml-2" onclick="event.stopPropagation(); event.preventDefault();">
                            <input type="checkbox" 
                                   class="custom-control-input toggle-active" 
                                   id="toggle-{{ $section->id }}"
                                   data-id="{{ $section->id }}" 
                                   {{ $section->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="toggle-{{ $section->id }}"></label>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Content Panel --}}
    <div class="col-md-8 col-lg-9">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title" id="panelTitle">
                    <i class="fas fa-mouse-pointer mr-2"></i>Chọn module để chỉnh sửa
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="btnSave" style="display: none;">
                        <i class="fas fa-save mr-1"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
            <div class="card-body" id="editPanel">
                {{-- Empty State --}}
                <div class="text-center py-5" id="emptyState">
                    <i class="fas fa-hand-pointer fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chọn một module từ danh sách bên trái để chỉnh sửa</p>
                </div>
                {{-- Form Container --}}
                <div id="editFormContainer" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    const $moduleItems = $('.module-item');
    const $emptyState = $('#emptyState');
    const $editFormContainer = $('#editFormContainer');
    const $panelTitle = $('#panelTitle');
    const $btnSave = $('#btnSave');
    let currentSectionId = null;

    // Click module to load form
    $moduleItems.on('click', function() {
        const $this = $(this);
        const id = $this.data('id');
        const name = $this.data('name');
        
        $moduleItems.removeClass('active');
        $this.addClass('active');
        
        loadEditForm(id, name);
    });

    // Load edit form
    function loadEditForm(id, name) {
        currentSectionId = id;
        $emptyState.hide();
        $editFormContainer.show().html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Đang tải...</p></div>');
        $panelTitle.html('<i class="fas fa-edit mr-2"></i>' + name);
        $btnSave.show();

        $.get('/admin/homepage-sections/' + id + '/edit-form')
            .done(function(html) {
                $editFormContainer.html(html);
            })
            .fail(function() {
                $editFormContainer.html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Có lỗi xảy ra khi tải form</div>');
            });
    }

    // Save form
    $btnSave.on('click', function() {
        if (!currentSectionId) return;
        
        const $form = $('#sectionEditForm');
        if (!$form.length) return;

        const formData = new FormData($form[0]);
        $btnSave.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang lưu...');

        $.ajax({
            url: '/admin/homepage-sections/' + currentSectionId,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .done(function(res) {
            if (res.success) {
                toastr.success(res.message || 'Đã lưu thành công!');
            }
        })
        .fail(function() {
            toastr.error('Có lỗi xảy ra khi lưu');
        })
        .always(function() {
            $btnSave.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Lưu thay đổi');
        });
    });

    // Toggle active
    $('.toggle-active').on('change', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        const $item = $(this).closest('.module-item');
        
        $.post('/admin/homepage-sections/' + id + '/toggle', {
            _token: '{{ csrf_token() }}'
        })
        .done(function(res) {
            if (res.success) {
                $item.toggleClass('text-muted', !res.is_active);
                toastr.success(res.message);
            }
        });
    });

    // --- LFM & IMAGE PICKER LOGIC (Delegated) ---

    // 1. Global State
    let currentInputId = null;
    let currentPreviewId = null;
    let isMultiple = false;

    // 2. Click Handler (Delegation)
    $(document).on('click', '.btn-lfm-picker', function(e) {
        e.preventDefault();
        const inputId = $(this).data('input');
        const previewId = $(this).data('preview');
        const type = $(this).data('type') || 'image';
        const multiple = $(this).data('multiple') == 1;

        openLFM(inputId, previewId, type, multiple);
    });

    // 3. Open LFM Window
    window.openLFM = function(inputId, previewId, type, multiple) {
        currentInputId = inputId;
        currentPreviewId = previewId;
        isMultiple = multiple;
        
        let route_prefix = '/laravel-filemanager';
        window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
    };

    // 4. Callback from LFM
    window.SetUrl = function(items) {
        if (!items || items.length === 0 || !currentInputId) return;

        const input = document.getElementById(currentInputId);
        const previewList = document.getElementById(currentPreviewId + '_list');
        const previewWrapper = document.getElementById(currentPreviewId + '_wrapper');

        const cleanUrl = (url) => {
            try {
                const u = new URL(url);
                return u.pathname.replace(/^\//, ''); 
            } catch (e) {
                return url.replace(/^\//, '');
            }
        };

        if (isMultiple) {
            // --- Logic Multiple ---
            let currentPaths = [];
            try {
                currentPaths = JSON.parse(input.value) || [];
            } catch (e) {
                currentPaths = [];
            }
            if (!Array.isArray(currentPaths)) currentPaths = [];

            items.forEach(item => {
                const path = cleanUrl(item.url);
                if (!currentPaths.includes(path)) {
                    currentPaths.push(path);
                }
            });

            input.value = JSON.stringify(currentPaths);
            input.dispatchEvent(new Event('change'));

            renderPreviewGrid(previewList, currentPaths, currentInputId);
            if (previewWrapper) previewWrapper.style.display = 'block';

        } else {
            // --- Logic Single ---
            const path = cleanUrl(items[0].url);
            
            input.value = path;
            input.dispatchEvent(new Event('change'));

            // Render single preview
            if (previewList) {
                 renderPreviewGrid(previewList, [path], currentInputId, false);
            }
            
            if (previewWrapper) previewWrapper.style.display = 'block';

            // Ensure clear button exists
            ensureClearButton(input, currentInputId, currentPreviewId);
        }
    };

    // 5. Render Preview Helper
    function renderPreviewGrid(container, paths, inputId, showRemove = true) {
        if (!container) return;
        container.innerHTML = ''; 

        paths.forEach(path => {
            const col = document.createElement('div');
            col.className = 'col-md-3 col-4 mb-2 preview-item position-relative';
            const fullUrl = '/' + path.replace(/^\//, '');

            let html = `
                <div class="img-thumbnail" style="height: 150px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa;">
                    <img src="${fullUrl}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                </div>
            `;
            
            // Logic check showRemove: nếu tham số showRemove=true HOẶC container là multiple
            const isMultiContainer = $('#' + inputId + '_container').data('multiple') === true;
            
            if (showRemove || isMultiContainer) {
                 html += `
                    <button type="button" class="btn btn-sm btn-danger position-absolute btn-remove-item" 
                            style="top: 5px; right: 20px;"
                            data-url="${path}"
                            data-input="${inputId}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            }
            col.innerHTML = html;
            container.appendChild(col);
        });
    }

    // 6. Remove Item Handler (Delegation)
    $(document).on('click', '.btn-remove-item', function(e) {
        e.preventDefault();
        const pathToRemove = $(this).data('url');
        const inputId = $(this).data('input');
        const input = document.getElementById(inputId);
        
        if (input) {
            try {
                let paths = JSON.parse(input.value);
                paths = paths.filter(p => p !== pathToRemove);
                input.value = JSON.stringify(paths);
                input.dispatchEvent(new Event('change'));
                
                const previewList = document.querySelector(`#${inputId}_container .row`);
                renderPreviewGrid(previewList, paths, inputId);
                
                if (paths.length === 0) {
                     document.querySelector(`#${inputId}_container .media-preview-grid`).style.display = 'none';
                }
            } catch(e) { console.error(e); }
        }
    });

    // 7. Clear Button Logic (Single)
    function ensureClearButton(input, inputId, previewId) {
        // Check if multiple
        const $wrapper = $('#' + inputId + '_container');
        if ($wrapper.data('multiple') === true) return;

        // Check if exists
        if ($wrapper.find('.btn-clear-image').length === 0) {
            const btnHtml = `
                <button type="button" class="btn btn-outline-danger btn-clear-image" 
                        data-input="${inputId}" data-preview="${previewId}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            $wrapper.find('.input-group-append').append(btnHtml);
        }
    }

    // 8. Clear Button Click Handler (Delegation)
    $(document).on('click', '.btn-clear-image', function(e) {
        e.preventDefault();
        const inputId = $(this).data('input');
        const previewId = $(this).data('preview');
        
        $('#' + inputId).val('').trigger('change');
        $('#' + previewId + '_wrapper').hide();
        $(this).remove();
    });
});
</script>
@endpush
