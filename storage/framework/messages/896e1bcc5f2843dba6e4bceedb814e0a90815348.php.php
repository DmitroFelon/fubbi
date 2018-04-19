<div class="form-group" id="<?php echo e($name); ?>-group">
    <?php echo e(Form::label($name, $label, ['class' => 'control-label'])); ?>

    <?php echo e(Form::select(
        $name,
        $list,
        $selected,
        array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $select_attributes)) ,
        $options_attributes); ?>

    <div class="text-muted"><?php echo e($description); ?></div>
</div>