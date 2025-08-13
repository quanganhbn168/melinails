<div class="btn-group" role="group">
    <a href="<?php echo e($editUrl); ?>" class="btn btn-warning btn-sm">
        <i class="far fa-edit"></i>
    </a>
    <form action="<?php echo e($deleteUrl); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');" style="display:inline-block;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="far fa-trash-alt"></i>
        </button>
    </form>
</div><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/components/action-buttons.blade.php ENDPATH**/ ?>