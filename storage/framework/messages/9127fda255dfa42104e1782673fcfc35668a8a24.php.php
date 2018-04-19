<div class="social-feed-box">
    <div class="social-avatar">
        <div class="media-body">
            <a target="_blank" href="<?php echo e(url()->action('UserController@show', $comment->creator)); ?>">
                <?php echo e($comment->creator->name); ?>

            </a>
            <small class="text-muted">
                <?php echo e($comment->created_at->diffForHumans()); ?>

            </small>
        </div>
    </div>
    <div class="social-body">
        <?php if($comment->title): ?>
            <h3><?php echo e($comment->title); ?></h3>
        <?php endif; ?>
        <p>
            <?php echo e($comment->body); ?>

        </p>
    </div>
</div>
