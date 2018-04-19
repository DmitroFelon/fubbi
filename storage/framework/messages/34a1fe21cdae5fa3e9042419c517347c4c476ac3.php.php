<?php $__env->startSection('before-content'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                <?php echo e($user->name); ?>

                <span class="label label-default pull-right"><?php echo e($user->roles()->first()->display_name); ?></span>
            </h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <table class="table small">
                        <tbody>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e(__('Phone:')); ?>

                                </strong>
                                <?php echo e($user->phone); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e(__('Email:')); ?>

                                </strong>
                                <?php echo e($user->email); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e($user->projects->count()); ?>

                                </strong>
                                <?php echo e(__('Projects')); ?>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>