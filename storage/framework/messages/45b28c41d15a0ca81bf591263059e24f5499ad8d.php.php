<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5><?php echo e(__('Require')); ?></h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <dl class="dl-horizontal">
                <?php $__currentLoopData = $project->plan_metadata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <dt>
                        <?php echo e(ucwords( str_replace('_',' ',$key) )); ?>:
                    </dt>
                    <dd>
                        <?php if($project->isModified($key)): ?>
                            <?php echo e((is_bool($project->getModified($key)))
                            ? ($project->getModified($key)) ?__('Yes') : __('No') : $project->getModified($key)); ?>

                        <?php else: ?>
                            <?php echo e((is_bool($value)) ? ($value) ?__('Yes') : __('No') : $value); ?>

                        <?php endif; ?>

                    </dd>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        </div>
    </div>
</div>


<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5><?php echo e(__('Completed')); ?></h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <dl class="dl-horizontal">
                <?php $__currentLoopData = $project->plan_metadata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(in_array($key, $project->getCountableServices())): ?>
                        <dt>
                            <?php echo e(ucwords( str_replace('_',' ',$key) )); ?>:
                        </dt>
                        <dd>
                            <a><?php echo e(__('Outlines')); ?> : <?php echo e($project->getServiceOutlines($key)->count()); ?> </a>
                            /
                            <a><?php echo e(__('Completed')); ?>: <?php echo e($project->getServiceResult($key)->count()); ?> </a>
                        </dd>
                    <?php else: ?>
                        <dt><?php echo e(ucwords( str_replace('_',' ',$key) )); ?>:</dt>
                        <dd>-</dd>
                    <?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        </div>
    </div>
</div>
