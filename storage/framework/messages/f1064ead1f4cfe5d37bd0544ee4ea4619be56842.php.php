<tr>
    <td class="client-avatar"><i class="fa fa-user fa-2x"></i></td>
    <td>
        <a target="_blank"
           href="<?php echo e(url()->action('UserController@show', $user)); ?>">
            <?php echo e($user->name); ?>

        </a>
    </td>
    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
    <td> <?php echo e($user->email); ?></td>
    <td class="contact-type"><i class="fa fa-phone"> </i></td>
    <td> <?php echo e($user->phone); ?></td>
    <td class="contact-type"><i class="fa fa-file-o"></i></td>
    <td><?php echo e($user->projects->count()); ?></td>
</tr>