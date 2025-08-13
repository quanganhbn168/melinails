<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
        <link rel="icon" href="<?php echo e(asset('favicon.png')); ?>" type="image/x-icon" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/dist/css/adminlte.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/bootstrap-icons/bootstrap-icons.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/select2/css/select2.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('plugins/sweetalert2/bootstrap-4.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('plugins/toastr/toastr.min.css')); ?>">
        
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/datatables-select/css/select.bootstrap4.min.css')); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="shortcut icon" href="/favicon/favicon.ico">
        
        <?php echo $__env->yieldPushContent('css'); ?>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php echo $__env->make('partials.admin.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h2><?php echo $__env->yieldContent('content_header'); ?></h2>
                            </div>
                            <div class="col-sm-6">
                                <?php echo $__env->make('components.breadcrumb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                            <!-- Default box -->
                                <?php echo $__env->yieldContent('content'); ?>
                            <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </section>
            <!-- /.content -->
            </div>
            <?php echo $__env->make('partials.admin.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

    <script src="<?php echo e(asset('js/jquery-3.7.1.min.js')); ?>"></script>
    
    <script src="<?php echo e(asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/toastr/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/select2/js/select2.full.min.js')); ?>"></script>

    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/DataTables/buttons.server-side.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/pdfmake/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/pdfmake/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.print.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-select/js/dataTables.select.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/datatables-select/js/select.bootstrap4.min.js')); ?>"></script>

    <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
        <script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
</script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<?php if(session('success')): ?>
    <script>
        Toast.fire({
            icon: 'success',
            title: <?php echo json_encode(session('success'), 15, 512) ?>
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        Toast.fire({
            icon: 'error',
            title: <?php echo json_encode(session('error'), 15, 512) ?>
        });
    </script>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (form.classList.contains('form-delete')) {
                return; 
            }
            // Chặn submit nếu đã xử lý rồi
            if (form.classList.contains('submitting')) {
                e.preventDefault();
                return false;
            }

            // Gắn cờ để không cho submit 2 lần
            form.classList.add('submitting');

            // Khóa tất cả các nút submit trong form
            form.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
            });

            // Lấy nút được bấm
            const clickedBtn = document.activeElement;
            let action = clickedBtn?.value || 'save';

            let message = 'Đang xử lý...';
            if (action === 'update') message = 'Đang cập nhật...';
            if (action === 'save') message = 'Đang tạo mới...';
            if (action === 'save_new') message = 'Đang tạo mới...';

            Swal.fire({
                title: message,
                text: 'Vui lòng chờ trong giây lát...',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cho phép submit tiếp (không prevent)
        });
    });
});
</script>

<script>
    $('.btn-toggle').on('click', function () {
        const btn = $(this);
        const id = btn.data('id');
        const model = btn.data('model');
        const field = btn.data('field');

        $.ajax({
            url: '<?php echo e(route('admin.toggle')); ?>',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                id: id,
                model: model,
                field: field
            },
            success: function (res) {
                btn.text(res.value ? '✓' : '✗');

                Toast.fire({
                    icon: 'success',
                    title: res.message || 'Đã cập nhật thành công'
                });
            },
            error: function (xhr) {
                let message = 'Đã xảy ra lỗi';

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    message = xhr.responseJSON.error;
                }

                Toast.fire({
                    icon: 'error',
                    title: message
                });
            }
        });
    });
</script>

        
        <?php echo $__env->yieldPushContent('js'); ?>
    </body>
</html>
<?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/layouts/admin.blade.php ENDPATH**/ ?>