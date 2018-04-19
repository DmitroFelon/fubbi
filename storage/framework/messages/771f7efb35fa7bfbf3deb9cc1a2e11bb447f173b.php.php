
<div class="col col-xs-3">
    <div class="file-box">
        <div class="file">
            <span class="corner"></span>
            <div class="icon">
                <?php if($media->mime_type == 'image/jpeg' or $media->mime_type == 'image/png'): ?>
                    <a href="<?php echo e($media->getFullUrl()); ?>"
                       target="_blank"
                       data-gallery="<?php echo e($media->collection_name); ?>">
                        <img class="blueimp-gallery-image"
                             src="<?php echo e($media->getFullUrl()); ?>">
                    </a>
                <?php else: ?>
                    <a target="_blank" href="<?php echo e($media->getFullUrl()); ?>">
                        <i class="fa fa-file"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div class="file-name">
                <a target="_blank" href="<?php echo e($media->getFullUrl()); ?>">
                    <?php echo e($media->file_name); ?>

                </a>
                <br/>
                <small>Added: <?php echo e($media->created_at->format('Y-m-d')); ?></small>
            </div>
        </div>
    </div>
</div>