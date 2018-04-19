<li>
    <div class="dropdown-messages-box">
        <div>
            <small class="pull-right text-navy"><?php echo e($notification->created_at->diffForHumans()); ?></small>
            <?php if(isset($notification->data['link'])): ?>
                <a href="<?php echo e(url('notification/show/'.$notification->id)); ?>">
                    <?php echo e($notification->data['message']); ?>.
                </a>
            <?php else: ?>
                <?php echo e($notification->data['message']); ?>.
            <?php endif; ?>
            <br>
            <small class="text-muted"><?php echo e($notification->created_at); ?></small>
        </div>
    </div>
</li>
<li class="divider"></li>