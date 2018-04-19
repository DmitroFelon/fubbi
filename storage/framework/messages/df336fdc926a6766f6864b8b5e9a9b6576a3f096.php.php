<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('Reset Password')); ?></h2>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox-content">
        <form class="form-horizontal" method="POST" action="<?php echo e(route('password.request')); ?>">
            <?php echo e(csrf_field()); ?>


            <input type="hidden" name="token" value="<?php echo e($token); ?>">

            <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                <label for="email" class="col-md-4 control-label"><?php echo e(__('E-Mail Address')); ?></label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email"
                           value="<?php echo e(isset($email) ? $email : old('email')); ?>" required autofocus>

                    <?php if($errors->has('email')): ?>
                        <span class="help-block">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                <label for="password" class="col-md-4 control-label"><?php echo e(__('Password')); ?></label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    <?php if($errors->has('password')): ?>
                        <span class="help-block">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
                <label for="password-confirm" class="col-md-4 control-label"><?php echo e(__('Confirm Password')); ?></label>
                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                           required>

                    <?php if($errors->has('password_confirmation')): ?>
                        <span class="help-block">
                                        <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        <?php echo e(__('Reset Password')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>