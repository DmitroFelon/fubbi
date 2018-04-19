<div class="row">
    <?php $__currentLoopData = $plan->metadata->split(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <small>
                                <?php echo e(__('Default value: %s', $project->plan->metadata->$key)); ?>

                            </small>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="row">
    <?php echo Form::submit('Update', ['class'=>'']); ?>

</div>