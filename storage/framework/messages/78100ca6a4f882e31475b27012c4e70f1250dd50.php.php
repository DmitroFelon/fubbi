<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Invoice</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #fff;
            background-image: none;
            font-size: 12px;
        }
        address{
            margin-top:15px;
        }
        h2 {
            font-size:28px;
            color:#cccccc;
        }
        .container {
            padding-top:30px;
        }
        .invoice-head td {
            padding: 0 8px;
        }
        .invoice-body{
            background-color:transparent;
        }
        .logo {
            padding-bottom: 10px;
        }
        .table th {
            vertical-align: bottom;
            font-weight: bold;
            padding: 8px;
            line-height: 20px;
            text-align: left;
        }
        .table td {
            padding: 8px;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
            border-top: 1px solid #dddddd;
        }
        .well {
            margin-top: 15px;
        }
    </style>
</head>

<body>
<div class="container">
    <table style="margin-left: auto; margin-right: auto" width="550">
        <tr>
            <td width="160">
                &nbsp;
            </td>

            <!-- Organization Name / Image -->
            <td align="right">
                <strong><?php echo e(isset($header) ? $header : $vendor); ?></strong>
            </td>
        </tr>
        <tr valign="top">
            <td style="font-size:28px;color:#cccccc;">
                    Receipt
            </td>

            <!-- Organization Name / Date -->
            <td>
                <br><br>
                <strong>To:</strong> <?php echo e($owner->email ?: $owner->name); ?>

                <br>
                <strong>Date:</strong> <?php echo e($invoice->date()->toFormattedDateString()); ?>

            </td>
        </tr>
        <tr valign="top">
            <!-- Organization Details -->
            <td style="font-size:9px;">
                <?php echo e($vendor); ?><br>
                <?php if(isset($street)): ?>
                    <?php echo e($street); ?><br>
                <?php endif; ?>
                <?php if(isset($location)): ?>
                    <?php echo e($location); ?><br>
                <?php endif; ?>
                <?php if(isset($phone)): ?>
                    <strong>T</strong> <?php echo e($phone); ?><br>
                <?php endif; ?>
                <?php if(isset($vendorVat)): ?>
                    <?php echo e($vendorVat); ?><br>
                <?php endif; ?>
                <?php if(isset($url)): ?>
                    <a href="<?php echo e($url); ?>"><?php echo e($url); ?></a>
                <?php endif; ?>
            </td>
            <td>
                <!-- Invoice Info -->
                <p>
                    <strong>Product:</strong> <?php echo e($product); ?><br>
                    <strong>Invoice Number:</strong> <?php echo e(isset($id) ? $id : $invoice->id); ?><br>
                </p>

                <!-- Extra / VAT Information -->
                <?php if(isset($vat)): ?>
                    <p>
                        <?php echo e($vat); ?>

                    </p>
                <?php endif; ?>

                <br><br>

                <!-- Invoice Table -->
                <table width="100%" class="table" border="0">
                    <tr>
                        <th align="left">Description</th>
                        <th align="right">Date</th>
                        <th align="right">Amount</th>
                    </tr>

                    <!-- Existing Balance -->
                    <tr>
                        <td>Starting Balance</td>
                        <td>&nbsp;</td>
                        <td><?php echo e($invoice->startingBalance()); ?></td>
                    </tr>

                    <!-- Display The Invoice Items -->
                    <?php $__currentLoopData = $invoice->invoiceItems(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td colspan="2"><?php echo e($item->description); ?></td>
                            <td><?php echo e($item->total()); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Display The Subscriptions -->
                    <?php $__currentLoopData = $invoice->subscriptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>Subscription (<?php echo e($subscription->quantity); ?>)</td>
                            <td>
                                <?php echo e($subscription->startDateAsCarbon()->formatLocalized('%B %e, %Y')); ?> -
                                <?php echo e($subscription->endDateAsCarbon()->formatLocalized('%B %e, %Y')); ?>

                            </td>
                            <td><?php echo e($subscription->total()); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Display The Discount -->
                    <?php if($invoice->hasDiscount()): ?>
                        <tr>
                            <?php if($invoice->discountIsPercentage()): ?>
                                <td><?php echo e($invoice->coupon()); ?> (<?php echo e($invoice->percentOff()); ?>% Off)</td>
                            <?php else: ?>
                                <td><?php echo e($invoice->coupon()); ?> (<?php echo e($invoice->amountOff()); ?> Off)</td>
                            <?php endif; ?>
                            <td>&nbsp;</td>
                            <td>-<?php echo e($invoice->discount()); ?></td>
                        </tr>
                    <?php endif; ?>

                    <!-- Display The Tax Amount -->
                    <?php if($invoice->tax_percent): ?>
                        <tr>
                            <td>Tax (<?php echo e($invoice->tax_percent); ?>%)</td>
                            <td>&nbsp;</td>
                            <td><?php echo e(Laravel\Cashier\Cashier::formatAmount($invoice->tax)); ?></td>
                        </tr>
                    <?php endif; ?>

                    <!-- Display The Final Total -->
                    <tr style="border-top:2px solid #000;">
                        <td>&nbsp;</td>
                        <td style="text-align: right;"><strong>Total</strong></td>
                        <td><strong><?php echo e($invoice->total()); ?></strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
