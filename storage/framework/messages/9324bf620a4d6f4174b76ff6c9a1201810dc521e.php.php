<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            <?php if(auth()->guard()->check()): ?>
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs">
                                    <strong class="font-bold">
                                        <?php echo e(\Illuminate\Support\Facades\Auth::user()->name); ?>

                                    </strong>
                                </span>
                                <span class="text-muted text-xs block">
                                    <?php echo e(__('Fast actions')); ?><b class="caret"></b>
                                </span>
                            </span>
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li>
                                <a href="#"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                        <?php endif; ?>
                    </div>
                    <div class="logo-element">
                        IN+
                    </div>
                </li>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="<?php echo e(isActiveRoute($item['url'])); ?>">
                        <a href="<?php echo e($item['url']); ?>">
                            <i class="<?php echo e($item['icon']); ?>"></i>
                            <span class="nav-label"><?php echo e($item['name']); ?></span>
                            <?php if($item['url'] == '/alerts'): ?>
                                <span class="badge">
                                    <?php echo e(\Illuminate\Support\Facades\Auth::user()->unreadNotifications->count()); ?>

                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if(auth()->guard()->guest()): ?>
            <li class="nav-header">
                <div class="logo-element">
                    IN+
                </div>
            </li>
            <li class="<?php echo e(isActiveRoute('login')); ?>">
                <a href="<?php echo e(url('login')); ?>">
                    <i class="fa fa-sign-in"></i>
                    <span class="nav-label"><?php echo e(__('Login')); ?></span>
                </a>
            </li>
            <li class="<?php echo e(isActiveRoute('register')); ?>">
                <a href="<?php echo e(url('register')); ?>">
                    <i class="fa fa-plus"></i>
                    <span class="nav-label"><?php echo e(__('Registration')); ?></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
