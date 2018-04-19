<div class="row">
    <div class="col-lg-12">
        <div class="m-b-md">
            <?php echo $__env->make('entity.project.partials.show.action-buttons', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="clear-both"></div>
        <dl class="dl-horizontal">
            <dt><?php echo e(__('Status')); ?>:</dt>
            <dd>
                    <span class="label label-primary">
                        <?php echo e(ucfirst(str_replace('_',' ',$project->state))); ?>

                    </span>
            </dd>
        </dl>
    </div>
</div>
<div class="row">
    <div class="col-lg-5">
        <dl class="dl-horizontal">
            <dt>Client:</dt>
            <dd>
                <a target="_blank" href="<?php echo e(url()->action('UserController@show', $project->client)); ?>">
                    <?php echo e($project->client->name); ?>

                </a>
            </dd>
            <dt><?php echo e(__('Subscription Plan')); ?>:</dt>
            <dd>
                <?php echo e(title_case(str_replace('-',' ',$project->subscription->stripe_plan))); ?>

                <?php if($project->getModifications()): ?>
                    <small>(<?php echo e(__('modified')); ?>)</small>
                <?php endif; ?>
            </dd>
            <dt><?php echo e(__('Billing Cycle')); ?>:</dt>
            <dd>1 <?php echo e(__('month')); ?></dd>
            <dt><?php echo e(__('Messages')); ?>:</dt>
            <dd><?php echo e($project->commentCount()); ?></dd>
        </dl>
    </div>
    <div class="col-lg-7" id="cluster_info">
        <dl class="dl-horizontal">
            <dt><?php echo e(__('Last Updated')); ?>:</dt>
            <dd>
                <?php echo e($project->updated_at); ?>

                <small class="text-muted">(<?php echo e($project->updated_at->diffForHumans()); ?>)</small>
            </dd>
            <dt><?php echo e(__('Created')); ?>:</dt>
            <dd>
                <?php echo e($project->created_at); ?>

                <small class="text-muted">(<?php echo e($project->created_at->diffForHumans()); ?>)</small>
            </dd>
            <dt><?php echo e(__('Participants')); ?>:</dt>
            <dd class="project-people">
                <?php $__currentLoopData = $project->workers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $worker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <a target="_blank" href="<?php echo e(url()->action('UserController@show', $worker)); ?>">
                            <?php echo e($worker->name); ?>

                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dd>
        </dl>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <dl class="dl-horizontal">
            <dt><?php echo e(__('Completed')); ?>:</dt>
            <dd>
                <div class="progress progress-striped active m-b-sm">
                    <div style="width: 0.5%;" class="progress-bar">
                        <span>0.5%</span>
                    </div>
                </div>
            </dd>
        </dl>
    </div>
</div>