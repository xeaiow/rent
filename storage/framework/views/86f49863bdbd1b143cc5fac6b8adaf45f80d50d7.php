<!DOCTYPE html>
<html>
    <head>
        <title>中原資管教室預約系統後台</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?php $__env->startSection('style'); ?>
            <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>" />
            <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>" />
            <!-- Tocas UI：CSS 與元件 -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.css" />
            <!-- Tocas JS：模塊與 JavaScript 函式 -->
            <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
        <?php echo $__env->yieldSection(); ?>
    </head>
    <body id="app">

        <section>
            <?php echo $__env->yieldContent('content'); ?>
        </section>

        <?php $__env->startSection('script'); ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.js"></script>
            <script src="<?php echo e(asset('js/moment.js')); ?>"></script>
            <script src="<?php echo e(asset('js/admin.js')); ?>"></script>
            <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <?php echo $__env->yieldSection(); ?>
    </body>
</html>