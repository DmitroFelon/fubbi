<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2><?php echo e(__('Notifications')); ?></h2>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-push-1 col-lg-push-1">
                    <div class="row">
                        <?php if($has_unread_notifications): ?>
                            <a href="<?php echo e(url('notification/read/')); ?>" class="btn btn-default btn-xs pull-right">
                                <?php echo e(__('Mart all as read')); ?>

                            </a>
                            <br>
                        <?php endif; ?>
                    </div>
                    <div class="activity-stream">
                        <?php echo $__env->renderEach('entity.notification.partials.page-row', $page_notifications, 'notification', 'entity.notification.partials.page-row-empty'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>