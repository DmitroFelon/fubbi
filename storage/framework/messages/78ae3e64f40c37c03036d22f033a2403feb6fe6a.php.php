<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <?php if(auth()->guard()->check()): ?>
        <ul class="nav navbar-top-links navbar-right">
            <?php echo $__env->make('partials.navbar-elements.navbar-messages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('partials.navbar-elements.navbar-alerts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <li>
                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="#">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </li>
        </ul>
        <?php endif; ?>
    </nav>
</div>
