<form method="get" class="input-group">
    <input type="text"
           value="<?php echo e((request()->input('s'))?request()->input('s'):''); ?>"
           name="s"
           data-provide="typeahead"
           data-source='<?php echo e($search_suggestions); ?>'
           placeholder="<?php echo e(__('Search user')); ?>"
           class="input form-control">
    <span class="input-group-btn">
        <button type="submit" class="btn btn btn-primary">
            <i class="fa fa-search"></i><?php echo e(__('Search')); ?>

        </button>
    </span>
</form>