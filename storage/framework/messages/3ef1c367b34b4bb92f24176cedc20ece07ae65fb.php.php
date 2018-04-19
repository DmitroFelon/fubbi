<?php $__env->startSection('content'); ?>

    <div class="ibox">
        <div class="ibox-title">
            <h5><?php echo e(ucfirst($plan->name)); ?></h5>
            <div class="ibox-tools">
                <a target="_blank" href="<?php echo e(url()->action('PlanController@edit', $plan->id)); ?>"
                   class="btn btn-primary btn-xs"><?php echo e(__('Edit plan')); ?></a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="">
                <div class="row">
                    <?php $__currentLoopData = $plan->meta->split(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <table class="m-b-md">
                                <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th>
                                            <?php echo e(ucwords( str_replace('_',' ',$key) )); ?>:
                                        </th>
                                        <td>
                                            <?php if($value == 'true'): ?>
                                                <?php echo e(__('Yes')); ?>

                                            <?php elseif($value == 'false'): ?>
                                                <?php echo e(__('No')); ?>

                                            <?php else: ?>
                                                <?php echo e($value); ?>

                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h5><?php echo e(__('Projects with this plan')); ?></h5>
                        <ul>
                            <?php $__currentLoopData = $plan->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a target="_blank" href="<?php echo e(url()->action('ProjectController@show', $project)); ?>">
                                        <?php echo e($project->name); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>