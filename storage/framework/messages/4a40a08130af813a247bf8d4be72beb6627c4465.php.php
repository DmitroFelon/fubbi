<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4 p-md">
            <div>
                <a class="lead" target="_blank" href="<?php echo e(url()->action('ProjectController@show', $project)); ?>">
                    <?php echo e(title_case($project->subscription->name)); ?>

                </a><br>
                <small class="text-muted">
                    <?php echo e(__('Created at')); ?>: <?php echo e($project->subscription->created_at); ?>

                </small><br>
                <small class="text-muted">
                    <?php echo e(__('Selected plan')); ?>

                    : <?php echo e(title_case(str_replace('-',' ',$project->subscription->stripe_plan))); ?>

                </small>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox">
        <?php echo $__env->make('entity.project.form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>