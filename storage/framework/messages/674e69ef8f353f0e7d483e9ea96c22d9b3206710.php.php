<li class="dropdown">
    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
        <i class="fa fa-bell"></i>
        <?php if($notifications->count()): ?>
            <span class="label label-warning">
                <?php echo e($notifications->count()); ?>

            </span>
        <?php endif; ?>
    </a>
    <ul class="dropdown-menu dropdown-messages">
        <?php echo $__env->renderEach('partials.navbar-elements.alert-row',
            $notifications,
             'notification',
             'partials.navbar-elements.alert-row-empty'
        ); ?>
        <li>
            <div class="text-center link-block">
                <a href="<?php echo e(url('notification')); ?>">
                    <strong><?php echo e(__('See All Alerts')); ?></strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </li>
    </ul>
</li>