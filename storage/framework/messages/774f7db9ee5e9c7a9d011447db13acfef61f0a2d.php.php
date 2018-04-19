<?php $__currentLoopData = $keywords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword => $accepted): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <div class="i-checks">
            <label>
                <input
                        class="keywords-checkbox"
                        <?php echo e(($accepted)?'checked':''); ?>

                        type="checkbox"
                        name="keywords[<?php echo e($theme); ?>][<?php echo e($keyword); ?>]"
                        value="true"> <i></i>
                <?php echo e(ucfirst($keyword)); ?>

            </label>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script>
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
</script>
