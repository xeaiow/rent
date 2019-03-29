<!DOCTYPE html>
<html>
    <head>
        <title>中原資管教室預約系統後台</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?php $__env->startSection('style'); ?>
            <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>" />
            <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>" />
            <style>
                body {
                    background-color: #000;
                }
                div {
                    color: #38F16A;
                }
            </style>
        <?php echo $__env->yieldSection(); ?>
    </head>
    <body>

        <div id="admin-login">
            <div class="center-align">
                <pre>  
  ____ __   __ ___  __  __   ____   _____  _   _  _____ 
 / ___|\ \ / /|_ _||  \/  | |  _ \ | ____|| \ | ||_   _|    
| |     \ V /  | | | |\/| | | |_) ||  _|  |  \| |  | |  
| |___   | |   | | | |  | | |  _ < | |___ | |\  |  | |  
 \____|  |_|   |_| |_|  |_| |_| \_\|_____||_| \_|  |_|    
 __   _____  _____  _____ 
/  | |  _  ||  _  ||  ___|
`| | | |_| || |_| ||___ \ 
 | | \____ |\____ |    \ \
_| |_.___/ /.___/ //\__/ /
\___/\____/ \____/ \____/                           
                </pre>
            </div>
            <div>C:\CYIM\RENT <span id="login-note">(master -> origin)</span></div>
            <div id="login-press-next"></div>
            <div contenteditable="true" id="login-account"></div>
        </div>

        <?php $__env->startSection('script'); ?>
        
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <script>
                var account = [];
                var step = 0;

                $("#login-account").focus();
                $("#login-press-next").text("Enter your account.")

                $("#login-account").keypress(function(e) {
                    code = (e.keyCode ? e.keyCode : e.which);

                    if (code == 13) {
                        
                        e.preventDefault();

                        account.push($("#login-account").text());

                        if (step == 1) {
                            
                            axios.post('/pineapple/login', {
                                account: account[0],
                                password: account[1]
                            })
                            .then(function(res) {
                                if (!res.data.status) {
                                    $("#login-press-next").text('Please try again.');
                                    $("#login-account").text('');
                                    $("#login-account").css('color', '#38F16A');
                                    step = 0;
                                    account.length = 0;
                                    return false;
                                }

                                sessionStorage.setItem("cyimRentAccount", res.data.account);
                                sessionStorage.setItem("cyimRentToken", res.data.token);

                            window.location.href = '/pineapple';
                                
                            });
                            return false;
                        }
                        step++;

                        $("#login-press-next").text("Enter your password.");
                        $("#login-account").text('');
                        $("#login-account").css('color', '#000');
                    }
                });
            </script>
        <?php echo $__env->yieldSection(); ?>

    </body>