
@role(['admin', 'account_manager'])
    <?php echo $__env->make('entity.project.tabs.quiz', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('entity.project.tabs.keywords', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
@endrole


@role(['client'])
    <?php switch($step):
    case (\App\Models\Helpers\ProjectStates::PLAN_SELECTION): ?>
        <?php echo $__env->make('entity.project.tabs.plan', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php break; ?>
    <?php case (\App\Models\Helpers\ProjectStates::QUIZ_FILLING): ?>
        <?php echo $__env->make('entity.project.tabs.quiz', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php break; ?>
    <?php case (\App\Models\Helpers\ProjectStates::KEYWORDS_FILLING): ?>
        <?php echo $__env->make('entity.project.tabs.keywords', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php break; ?>
    <?php case (\App\Models\Helpers\ProjectStates::MANAGER_REVIEW): ?>
        <div class="text-primary">
            <?php echo e(__('Project is on manager review.')); ?>

        </div>
        <?php break; ?>
    <?php case (\App\Models\Helpers\ProjectStates::PROCESSING): ?>
        <div class="text-primary">
            <?php echo e(__('We are working on your project')); ?>

        </div>
        <div class="text-primary">
            <a href="#"><?php echo e(__('See results')); ?></a>
        </div>
        <?php break; ?>
    <?php default: ?>
        impossible state
    <?php endswitch; ?>
@endrole



