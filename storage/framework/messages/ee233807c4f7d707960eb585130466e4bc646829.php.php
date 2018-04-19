<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    <div class="ibox product-box">
        <div class="ibox-title">
            <h5><?php echo e($plan->name); ?></h5>
            <div class="ibox-tools">
                <span class="label-primary p-xxs b-r-sm">
                    <strong>
                        $ <?php echo e($plan->amount/100); ?>

                    </strong>
                </span>
            </div>
        </div>
        <div class="ibox-content no-paddings">
            <div class="p-xxs m-b-md border-bottom">
                <table class="m-b-md">
                    <?php $__currentLoopData = $plan->metadata->jsonSerialize(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th>
                                <small> <?php echo e(ucwords( str_replace('_',' ',$key) )); ?>:</small>
                            </th>
                            <td>
                                <small><?php echo e((is_bool($value)) ? ($value) ?__('Yes') : __('No') : $value); ?></small>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
            <div class="m-t text-righ">
                <a data-amount="<?php echo e($plan->amount/100); ?>"
                   data-plan="<?php echo e($plan->id); ?>"
                   href="#"
                   class="btn btn-sm btn-outline btn-primary subscribe-btn">
                    Subscribe <i class="fa fa-long-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>



