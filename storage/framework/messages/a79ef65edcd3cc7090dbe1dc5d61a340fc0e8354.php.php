<li class="<?php echo e((request()->input('r') == $role->name or !request()->input('r') and $loop->first)
                                    ?'active'
                                    :''); ?>">
    <a class="no-paddings" data-toggle="tab"
       href="#tab-<?php echo e($role->name); ?>">
        <i class="fa fa-user"></i> <?php echo e($role->display_name); ?>

        <?php if( isset($groupedByRoles[$role->name]) and $groupedByRoles[$role->name]->count()>0 ): ?>
            <span class="badge badge-primary">
                <?php echo e($groupedByRoles[$role->name]->count()); ?>

            </span>
        <?php else: ?>
            <span class="badge">0</span>
        <?php endif; ?>
    </a>
</li>