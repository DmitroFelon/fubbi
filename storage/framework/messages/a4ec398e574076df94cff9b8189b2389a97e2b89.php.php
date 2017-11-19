<?php $__env->startSection('content'); ?>
    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials.client.projects.card', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="project-add col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <div align="center" class="transparent">
            <i onclick="window.location.replace('<?php echo e(route('projects.create')); ?>');" class="fa fa-plus fa-4x"></i>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<style>
    .project-card{
        padding: 1em;
        color: white;
        font-size: 1.2em;
    }
    .project-card>div{
        background-color: #ffc675;
        height: 12em;
        padding: 1em;
    }
    .project-add>div>i{
        padding: 30% 0;
        color: lightgreen;
    }
    .project-add>div>i:hover{
        cursor: pointer;
    }
    .transparent{
        background-color: rgba(0,0,0,0)!important;
    }
    .project-workers{
        position: absolute;
        bottom: 1.5em;
    }
</style>
<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>