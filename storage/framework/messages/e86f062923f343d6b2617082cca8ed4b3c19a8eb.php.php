<tr>
    <td class="project-status">
        <span class="label label-primary"><?php echo e(ucfirst(str_replace('_',' ',$project->state))); ?></span>
    </td>
    <td class="project-title">
        <a href="<?php echo e(action('ProjectController@show', ['id' => $project->id])); ?>"><?php echo e($project->name); ?></a>
        <br/>
        <small>
            Created <?php echo e($project->created_at->format('Y-m-d')); ?>

        </small>
    </td>
    <td class="">
        <strong><?php echo e(__('Client')); ?>:</strong> <?php echo e($project->client->name); ?>

    </td>
    <td class="project-completion">
        <small><?php echo e(__('Completion with')); ?>: 0%</small>
        <div class="progress progress-mini">
            <div style="width: 0%;" class="progress-bar"></div>
        </div>
    </td>
    <td class="project-actions">
        <a href="<?php echo e(action('ProjectController@show', ['id' => $project->id])); ?>" class="btn btn-white btn-sm">
            <i class="fa fa-folder"></i> View
        </a>
        @role('client')
        <a href="<?php echo e(action('ProjectController@edit', ['id' => $project->id])); ?>" class="btn btn-white btn-sm">
            <i class="fa fa-pencil"></i> Edit
        </a>
        @endrole
    </td>
</tr>