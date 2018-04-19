<tr>
    <td class="project-status">
        <span class="label label-primary"></span>
    </td>
    <td class="project-title">
        <a href="<?php echo e(action('PlanController@show', ['id' => $plan->id])); ?>"><?php echo e($plan->name); ?></a>
        <br/>
        <small><?php echo e($plan->id); ?></small>
    </td>
    <td class="project-people">
        <?php echo e(__('Amount')); ?>: $<?php echo e($plan->amount/100); ?>

    </td>
    <td class="project-people">
        <?php echo e(__('Projects')); ?>: <?php echo e($plan->projects); ?>

    </td>
    <td class="project-actions">
        <a href="<?php echo e(action('PlanController@show', ['id' => $plan->id])); ?>" class="btn btn-white btn-sm">
            <i class="fa fa-folder"></i> View
        </a>
        @role('client')
        <a href="<?php echo e(action('PlanController@edit', ['id' => $plan->id])); ?>" class="btn btn-white btn-sm">
            <i class="fa fa-pencil"></i> Edit
        </a>
        @endrole
    </td>
</tr>