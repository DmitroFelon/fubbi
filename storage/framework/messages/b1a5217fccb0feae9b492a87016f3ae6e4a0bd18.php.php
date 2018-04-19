<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('Projects')); ?></h2>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox">
        <div class="ibox-title">
            <h5><?php echo e(__('All projects assigned to this account')); ?></h5>
            <div class="ibox-tools">
                <a target="_blank" href="<?php echo e(route('projects.create')); ?>"
                   class="btn btn-primary btn-xs"><?php echo e(__('Create new project')); ?></a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="project-list">
                <table class="table table-hover">
                    <tbody>
                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('entity.project.partials.row', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="text-center"><?php echo e($projects->links()); ?></div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>