<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="ibox">
		<div class="ibox-content">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row">
						<div class="col-md-6">
						</div>
						<div class="col-md-6">
							<div class="small text-right">
								<h5><?php echo e(__('Stats')); ?>:</h5>
								<div>
									<i class="fa fa-comments-o"> </i> <?php echo e($comments->count()); ?> <?php echo e(__('total')); ?>

								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<h2><?php echo e(__('Messages')); ?>:</h2>
							<?php echo $__env->renderEach('entity.comment.row', $comments, 'comment', 'entity.comment.row-empty'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>