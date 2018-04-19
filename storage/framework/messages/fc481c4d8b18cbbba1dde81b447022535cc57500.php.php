<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-title">
            <a href="<?php echo e(url()->action('TeamController@edit', $team)); ?>"
               class="btn btn-white btn-xs pull-right">
                <?php echo e(__('Edit team')); ?>

            </a>
            <h5><?php echo e($team->name); ?></h5>
        </div>
        <div class="ibox-content">
            <h4><?php echo e(__('Info about')); ?> <?php echo e($team->name); ?></h4>
            <p>
                <?php echo e($team->description); ?>

            </p>
            <div class="row  m-t-sm">
                <div class="col-sm-4">
                    <small><?php echo e(__('Active Projects')); ?></small>
                    1
                </div>
                <div class="col-sm-4">
                    <small><?php echo e(__('Total Projects')); ?></small>
                    12
                </div>
                <div class="col-sm-4">
                    <small><?php echo e(__('Rating')); ?></small>
                </div>
            </div>
            <hr>
            <div class="team-members">
                <h4><?php echo e(__('Team Members')); ?></h4>
                <?php $__currentLoopData = $team->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a target="_blank" href="<?php echo e(url()->action('UserController@show', $user)); ?>">
                        
                        <?php echo e($user->name); ?>

                    </a>
                    <?php if($user->id == $team->owner_id): ?>
                        <span class="label label-primary pull-right"><?php echo e(__('Owner')); ?></span>
                    <?php else: ?>
                        <span class="label label-default pull-right">
                                        <?php echo e($user->roles()->first()->display_name); ?>

                                    </span>
                    <?php endif; ?>
                    <br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>