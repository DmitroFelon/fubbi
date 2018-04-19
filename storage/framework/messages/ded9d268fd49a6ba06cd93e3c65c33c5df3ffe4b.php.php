<?php if(session('error')): ?>
    <div class="row wrapper border-bottom red-bg page-heading">
        <div class="col-sm-4 p-xs">
            <h3 class=""><?php echo e(session('error')); ?></h3>
        </div>
    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="row wrapper border-bottom green-bg page-heading">
        <div class="col-sm-4 p-xs">
            <h3 class=""><?php echo e(session('success')); ?></h3>
        </div>
    </div>
<?php endif; ?>

<?php if(session('info')): ?>
    <div class="row wrapper border-bottom blue-bg page-heading">
        <div class="col-sm-4 p-xs">
            <h3 class=""><?php echo e(session('info')); ?></h3>
        </div>
    </div>
<?php endif; ?>