
<div class="stream">
    <div class="stream-badge">
        <i class="fa fa-<?php echo e(\App\Notifications\NotificationPayload::getIcon($notification->type)); ?>"></i>
    </div>
    <div class="stream-panel">
        <div class="stream-info">
            <?php if(isset($notification->data['link']) and $notification->unread()): ?>
                <a class="client-link" href="<?php echo e(url('notification/show/'.$notification->id)); ?>">
                    <?php echo e($notification->data['message']); ?>

                    <i class="fa fa-level-up"></i>
                </a>
            <?php else: ?>
                <?php echo e($notification->data['message']); ?>

            <?php endif; ?>
            <span class="date"><?php echo e($notification->created_at->diffForHumans()); ?></span>
            <?php if($notification->unread()): ?>
                <a title="mark as read" href="<?php echo e(url('notification/read/'.$notification->id)); ?>" class="close pull-right">
                    <i class="fa fa-eye"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

</div>


