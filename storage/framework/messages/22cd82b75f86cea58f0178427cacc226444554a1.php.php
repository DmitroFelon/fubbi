<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#media_collection" class="panel-heading clickable">
                <span class="text-center"><?php echo e(__('Attached media files')); ?></span>
                <i class="pull-right fa fa-expand" aria-hidden="true"></i>
            </div>
            <div id="media_collection" class="panel-collapse panel-body collapse">
                <?php $__currentLoopData = \App\Models\Project::$media_collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!$project->hasMedia($collection)): ?> <?php continue; ?> <?php endif; ?>
                    <div class="row">
                        <div class="col col-xs-12">
                            <h3 class="text-center"><?php echo e(title_case(str_replace('_',' ',$collection))); ?></h3>
                            <?php echo $__env->renderEach('entity.project.partials.files-row', $project->getMedia($collection), 'media', 'entity.project.partials.files-row-empty'); ?>
                        </div>

                    </div>
                    <hr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>