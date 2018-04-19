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
            <h5></h5>
            <div class="ibox-tools">
                <a target="_blank" href="<?php echo e(url()->action('PlanController@edit', $plan->id)); ?>"
                   class="btn btn-primary btn-xs"><?php echo e(__('Edit plan')); ?></a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="project-list">
                <?php echo Form::open(['action' => ['PlanController@update', $plan->id], 'method' => 'put']); ?>

                <div class="row">
                    <?php $__currentLoopData = $plan->meta->split(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <table class="m-b-md">
                                <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th>
                                            <label for="<?php echo e($key); ?>">
                                                <?php echo e(ucwords( str_replace('_',' ',$key) )); ?>

                                            </label>
                                        </th>
                                        <td>
                                            <?php if($value == 'true' or $value == 'false'): ?>
                                                <input type="hidden" name="<?php echo e($key); ?>" value="false">
                                                <div class="i-checks">
                                                    <label>
                                                        <input
                                                                type="checkbox"
                                                                name="<?php echo e($key); ?>"
                                                                value="true"
                                                                <?php echo e(($value == 'true')?'checked="checked"':''); ?>> <i></i>
                                                    </label>
                                                </div>
                                            <?php else: ?>
                                                <input class="form-control" id="<?php echo e($key); ?>" name="<?php echo e($key); ?>"
                                                       value="<?php echo e($value); ?>">
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="row">
                    <?php echo Form::submit('Update'); ?>

                </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>