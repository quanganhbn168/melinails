<!-- Modal -->
<div class="modal fade" id="modaladdress" tabindex="-1" role="dialog" aria-labelledby="modaladdressLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modaladdressLabel">Danh sách chi nhánh</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				$branches = DB::table('branches')->where("status",1)->get();
				?>
				<div class="branches">
					<?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<div class="branch-item">
						<p class="branch-name text-primary font-weight-bold text-uppercase"><?php echo e($branch->name); ?></p>
						<p class="branch-name">
							<strong>Địa chỉ:</strong><?php echo e($branch->address); ?>

						</p>
						<p class="d-flex">
							<span><strong>Điện thoại</strong></span>
							<ul class="phone">
								<?php $__currentLoopData = explode("-",$branch->phone); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<li><a href="tel:<?php echo e($phone); ?>"><?php echo e($phone); ?></a></li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</ul>
						</p>
						<p><strong>Email:</strong><a href="mailto:<?php echo e($branch->email); ?>"><?php echo e($branch->email); ?></a></p>
					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
			</div>
		</div>
	</div>
</div><?php /**PATH D:\laragon\www\nangluongmattroi\resources\views/frontend/modal/branch.blade.php ENDPATH**/ ?>