<?php $__env->startSection('before-content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2><?php echo e(__('Registration')); ?></h2>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ibox-content">
        <form class="form-horizontal" method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo e(csrf_field()); ?>


            <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?>">
                <label for="first_name" class="col-md-4 control-label"><?php echo e(__('First Name')); ?></label>

                <div class="col-md-6">
                    <input id="first_name" type="text" class="form-control" name="first_name"
                           value="<?php echo e(old('first_name')); ?>" required
                           autofocus>

                    <?php if($errors->has('first_name')): ?>
                        <span class="help-block">
                                        <strong><?php echo e($errors->first('first_name')); ?></strong>
                                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?>">
                <label for="last_name" class="col-md-4 control-label"><?php echo e(__('Last Name')); ?></label>

                <div class="col-md-6">
                    <input id="last_name" type="text" class="form-control" name="last_name"
                           value="<?php echo e(old('last_name')); ?>" required
                           autofocus>

                    <?php if($errors->has('last_name')): ?>
                        <span class="help-block">
                                        <strong><?php echo e($errors->first('last_name')); ?></strong>
                                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                <label for="phone" class="col-md-4 control-label"><?php echo e(__('Phone')); ?></label>

                <div class="col-md-6">
                    <input id="phone" type="text" class="form-control" name="phone" value="<?php echo e(old('phone')); ?>" required
                           autofocus>

                    <?php if($errors->has('phone')): ?>
                        <span class="help-block">
                                        <strong><?php echo e($errors->first('phone')); ?></strong>
                                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                <label for="email" class="col-md-4 control-label"><?php echo e(__('E-Mail Address')); ?></label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>"
                           required>

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

            <div class="form-group">
                <label for="password-confirm" class="col-md-4 control-label"><?php echo e(__('Confirm Password')); ?></label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                           required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        <?php echo e(__('Register')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>