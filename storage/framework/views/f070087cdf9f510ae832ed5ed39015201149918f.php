<!--
                       _oo0oo_
                      o8888888o
                      88" . "88
                      (| -_- |)
                      0\  =  /0
                    ___/`---'\___
                  .' \\|     |// '.
                 / \\|||  :  |||// \
                / _||||| -:- |||||- \
               |   | \\\  -  /// |   |
               | \_|  ''\---/''  |_/ |
               \  .-\__  '-'  ___/-. /
             ___'. .'  /--.--\  `. .'___
          ."" '<  `.___\_<|>_/___.' >' "".
         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
         \  \ `_.   \_ __\ /__ _/   .-` /  /
     =====`-.____`.___ \_____/___.-`___.-'=====
                       `=---='
     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               佛祖保佑        永無 bug
-->
<!DOCTYPE html>
<html>
    <head>
        <title>中原資管教室預約系統</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?php $__env->startSection('style'); ?>
            <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>" />
            <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>" />
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap-grid.css' />
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css' />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css" />
            <style>
                body {
                    font-family: '微軟正黑體';
                    font-weigth: 400;
                    background-color: #e9eaed;
                    background-image: url("data:image/svg+xml,%3Csvg width='42' height='44' viewBox='0 0 42 44' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='Page-1' fill='none' fill-rule='evenodd'%3E%3Cg id='brick-wall' fill='%23cccccc' fill-opacity='0.4'%3E%3Cpath d='M0 0h42v44H0V0zm1 1h40v20H1V1zM0 23h20v20H0V23zm22 0h20v20H22V23z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                    overflow-x: hidden;
                }
            </style>
        <?php echo $__env->yieldSection(); ?>
    </head>
    <body>

        <section>
            <?php echo $__env->yieldContent('content'); ?>
        </section>
 
        <?php $__env->startSection('script'); ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
            <script src="<?php echo e(asset('js/index.js')); ?>"></script>
            <script src="<?php echo e(asset('js/moment.js')); ?>"></script>
            <script src="<?php echo e(asset('js/moment.distance.js')); ?>"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
            <script>
                $(function() {
                    
                    $('.tooltipped').tooltip();
                    $('select').formSelect();
                    $('.sidenav').sidenav();
                    $('.modal').modal({
                        dismissible: false,
                        opacity: .3,
                        onCloseEnd: function () {
                            $("#selectTimeTable").hide();
                            $("#room")[0].selectedIndex = 0;
                            $(".badge").remove();
                        }
                    });

                    $("#myRental").modal({
                        dismissible: true,
                        opacity: .7,
                    });

                    $('.tabs').tabs();
                    $('.materialboxed').materialbox();

                    hotkeys('alt+r,alt+q,alt+a,alt+d,option+r,option+q,option+a,option+d', function(event,handler) {
                        switch(handler.key){
                            case "alt+r":$(".my").click();break;
                            case "alt+q":$(".logout").click();break;
                            case "alt+a":$(".nav-about").click();break;
                            case "alt+d":$(".open-course").click();break;
                            case "option+r":$(".my").click();break;
                            case "option+q":$(".logout").click();break;
                            case "option+a":$(".nav-about").click();break;
                            case "option+d":$(".open-course").click();break;
                        }
                    });

                    console.log('%c ლ(ಠ_ಠლ) \n亂來我告訴老帥喔！', 'font-size:30pt; color: red');
                });
            </script>
        <?php echo $__env->yieldSection(); ?>
    </body>
</html>