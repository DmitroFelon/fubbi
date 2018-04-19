<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Fubbi</title>
    <link rel="stylesheet" href="<?php echo asset('css/vendor.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>"/>
</head>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<body>

<!-- Wrapper-->
<div id="wrapper">
    <div id="blueimp-gallery" class="blueimp-gallery">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>

    <!-- Navigation -->
<?php echo $__env->make('layouts.navigation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<!-- Page wraper -->
    <div id="page-wrapper" class="gray-bg">

    <?php if(auth()->guard()->check()): ?>
    <?php echo $__env->make('layouts.topnavbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->yieldContent('before-content'); ?>

    <?php echo $__env->make('partials.messages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- Main view  -->
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
        <!-- End Main view  -->

    <?php echo $__env->yieldContent('after-content'); ?>

    <!-- Footer -->
        <?php echo $__env->make('footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    </div>
    <!-- End page wrapper-->

</div>
<!-- End wrapper-->
<script>
    var stripe_pub = "<?php echo e(config('services.stripe.key')); ?>";
    var user = <?php echo json_encode((\Illuminate\Support\Facades\Auth::check())?\Illuminate\Support\Facades\Auth::user():'', 15, 512) ?>;
</script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script src="<?php echo asset('js/app.js'); ?>" type="text/javascript"></script>

<?php $__env->startSection('scripts'); ?>
<?php echo $__env->yieldSection(); ?>

</body>
</html>