<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('plans')); ?></h2>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                <?php echo e(__('Modify "%s" plan for project "%s"', [$plan->name, $project->name])); ?>

            </h5>
            <div class="ibox-tools">
                <a target="_blank" href="<?php echo e(url()->action('PlanController@edit', $plan->id)); ?>"
                   class="btn btn-primary btn-xs"><?php echo e(__('Edit plan')); ?></a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="project-list">
                <?php echo Form::open(['action' => ['Project\PlanController@update', $project->id, $plan->id], 'method' => 'put']); ?>

                <?php echo $__env->make('entity.plan.form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>