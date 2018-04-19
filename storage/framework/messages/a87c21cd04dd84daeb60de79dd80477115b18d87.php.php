<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('All plans')); ?></h2>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

        <div class="ibox">
            <div class="ibox-title">
                <h5><?php echo e(__('')); ?></h5>
                <div class="ibox-tools">
                    <a target="_blank" href="<?php echo e(route('plans.create')); ?>"
                       class="btn btn-primary btn-xs"><?php echo e(__('Create new plan')); ?></a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="project-list">
                    <table class="table table-hover">
                        <tbody>
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('entity.plan.partials.card', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>