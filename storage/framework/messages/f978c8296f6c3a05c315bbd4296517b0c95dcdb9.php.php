<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('Users')); ?></h2>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox">
        <div class="ibox-title">
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <?php echo $__env->make('entity.user.partials.search', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="clients-list">
                <span class="pull-right small text-muted"><?php echo e(__('Total')); ?>: <small><?php echo e($users->count()); ?></small></span>
                <ul class="nav nav-tabs">
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('entity.user.partials.top-tab', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <div class="tab-content">
                    <?php $__currentLoopData = $groupedByRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('entity.user.partials.tab', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>