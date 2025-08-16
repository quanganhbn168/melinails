<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />

    {{-- Google Font --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    
    {{-- Plugin CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    
    @stack('css')
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.admin.navbar')
        @include('partials.admin.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('content_header_title', 'Bảng điều khiển')</h1>
                        </div>
                        <div class="col-sm-6">
                            <x-breadcrumb :page-title="$pageTitle ?? null" />
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        
        @include('partials.admin.footer')
    </div>

    {{-- Core JS --}}
    <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    
    {{-- Plugin JS --}}
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    
    @stack('js')

    {{-- Global Scripts --}}
    <script>
        // Cấu hình Toastr mặc định
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // Kích hoạt select2 cho tất cả các element có class .select2
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });

        // "Chống đạn": Dùng Event Delegation để script hoạt động cả trong DataTables
        $(document).on('click', '.btn-toggle', function () {
            const btn = $(this);
            const data = btn.data();

            $.ajax({
                url: '{{ route('admin.toggle') }}', // Thay 'admin.toggle' bằng tên route toggle của bạn
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: data.id,
                    model: data.model,
                    field: data.field
                },
                success: function (res) {
                    if(res.value) {
                        btn.removeClass('btn-secondary').addClass('btn-success').text('Hiện');
                    } else {
                        btn.removeClass('btn-success').addClass('btn-secondary').text('Ẩn');
                    }
                    Toast.fire({ icon: 'success', title: res.message || 'Cập nhật thành công' });
                },
                error: function () {
                    Toast.fire({ icon: 'error', title: 'Đã xảy ra lỗi' });
                }
            });
        });

        // Script chặn double-submit
        $(document).on('submit', 'form:not(.form-delete)', function() {
            if ($(this).hasClass('submitting')) {
                return false;
            }
            $(this).addClass('submitting');
            $(this).find('button[type="submit"]').prop('disabled', true).prepend('<i class="fas fa-spinner fa-spin mr-1"></i>');
        });
    </script>
    
    {{-- Hiển thị thông báo từ session flash --}}
    @if(session('success'))
        <script>
            Toast.fire({ icon: 'success', title: @json(session('success')) });
        </script>
    @endif
    @if(session('error'))
        <script>
            Toast.fire({ icon: 'error', title: @json(session('error')) });
        </script>
    @endif
    @livewireScripts
</body>
</html>