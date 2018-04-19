<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('Project details')); ?></h2>
        </div>
    </div>

    <?php if(!$project->hasWorker() and \Illuminate\Support\Facades\Auth::user()->hasInvitetoProject($project->id) ): ?>
        <?php echo $__env->make('entity.project.partials.form.invite', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5><?php echo e($project->name); ?></h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <?php echo $__env->make('entity.project.partials.show.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                

                <?php echo $__env->make('entity.project.partials.show.metadata', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                

                

                <?php echo $__env->make('entity.project.partials.show.media', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                
            </div>
        </div>
    </div>

    @role(['account_manager', 'admin', 'writer'])
    <?php echo $__env->make('entity.project.worker-area.writer.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    @endrole

    <?php echo $__env->make('entity.comment.component', ['comments' => $project->comments], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>