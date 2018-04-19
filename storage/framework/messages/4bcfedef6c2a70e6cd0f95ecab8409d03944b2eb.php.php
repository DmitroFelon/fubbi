<div class="form-group" id="<?php echo e($name); ?>-group">
    <?php echo Form::label($name, $label, ['class' => 'control-label']); ?>

    <?php switch($type):
        case ('url'): ?>
            <?php echo Form::url($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('number'): ?>
            <?php echo Form::number($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('email'): ?>
            <?php echo Form::email($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('date'): ?>
            <?php echo Form::date($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('datetime'): ?>
            <?php echo Form::datetime($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('password'): ?>
            <?php echo Form::password($name, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('file'): ?>
            <?php echo Form::file($name, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php case ('textarea'): ?>
            <?php echo Form::textarea($name, null, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

            <?php break; ?>
        <?php default: ?>
            <?php echo Form::text($name, $value, array_merge(['class' => $errors->has($name)?'has-error form-control ':'form-control ' ], $attributes)); ?>

    <?php endswitch; ?>



    <div class="text-muted"><?php echo e($description); ?></div>
</div>

