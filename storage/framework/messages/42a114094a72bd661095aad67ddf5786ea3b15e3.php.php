<div id="tab-<?php echo e($role); ?>"
     class="tab-pane <?php echo e((request()->input('r') == $role or !request()->input('r') and $loop->first)?'active':''); ?>">
    <div class="full-height-scroll">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                <?php echo $__env->renderEach('entity.user.partials.row', $users, 'user', 'entity.user.partials.empty-row' ); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>