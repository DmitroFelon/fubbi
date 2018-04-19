@role(['account_manager', 'admin'])
<?php if($project->isOnReview()): ?>
    <a href="<?php echo e(url("project/accept_review/{$project->id}")); ?>" class="btn btn-primary btn-xs pull-right">
        <?php echo e(__('Accept review')); ?>

    </a>

    <a href="<?php echo e(url("project/reject_review/{$project->id}")); ?>" class="btn btn-danger btn-xs pull-right">
        <?php echo e(__('Reject review')); ?>

    </a>

<?php endif; ?>

<a href="<?php echo e(url()->action('Project\PlanController@edit', [$project, $project->plan->id])); ?>" class="btn btn-danger btn-xs pull-right">
    <?php echo e(__('Modify Plan')); ?>

</a>
@endrole()
@role(['client'])
<a href="<?php echo e(url()->action('ProjectController@edit', $project)); ?>" class="btn btn-white btn-xs pull-right">
    <?php echo e(__('Edit project')); ?>

</a>
@endrole()