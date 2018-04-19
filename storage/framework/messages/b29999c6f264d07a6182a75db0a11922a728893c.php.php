<div class="panel panel-default">
    <div data-toggle="collapse" href="#meta_data" class="panel-heading clickable">
        <span class="text-center"><?php echo e(__('Project quiz result')); ?></span>
        <i class="pull-right fa fa-expand" aria-hidden="true"></i>
    </div>
    <div id="meta_data" class="panel-collapse panel-body collapse">
        <?php $__currentLoopData = $project->getMeta(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta_key => $meta_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($meta_value == '' or empty($meta_value) or is_object($meta_value)): ?>
                <?php continue; ?>
            <?php endif; ?>
            <?php if(is_array($meta_value) and !empty($meta_value)): ?>
                <div>
                    <h4><?php echo e(title_case( str_replace('_', ' ', $meta_key))); ?></h4>
                    <ul>
                        <?php $__currentLoopData = $meta_value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_key => $sub_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($sub_value)): ?>
                            <li>
                                <strong>
                                    <?php echo e((is_int($sub_key))?'':title_case( str_replace('_', ' ', $sub_key))); ?>

                                </strong>
                                <?php echo $sub_value; ?>

                            </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <div>
                    <strong><?php echo e(title_case( str_replace('_', ' ', $meta_key))); ?>:</strong>
                    <?php echo $meta_value; ?>

                </div>
            <?php endif; ?>
            <?php if(!$loop->last): ?>
                <hr>
            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>