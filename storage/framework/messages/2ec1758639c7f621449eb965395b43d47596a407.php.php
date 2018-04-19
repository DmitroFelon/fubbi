<div class="ibox-title">
    <h5>Select Keywords</h5>
</div>
<div class="ibox-content">
    <?php echo Form::model($project,
    ['files' => true, 'method' => 'PUT', 'role'=>'form', 'id' => 'keywords-form', 'action' => ['ProjectController@update', $project->id]]); ?>

    <?php echo Form::hidden('_step', \App\Models\Helpers\ProjectStates::KEYWORDS_FILLING); ?>

    <?php echo Form::hidden('_project_id', $project->id); ?>


    <?php $__currentLoopData = collect(explode(',', $project->themes)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h1><?php echo e($theme); ?></h1>
        <fieldset data-mode="async" data-url="<?php echo e(url()->action('KeywordsController@index', [$project->id, $theme])); ?>">

        </fieldset>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php echo Form::close(); ?>


</div>
