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
        @section('style')
            <link rel="shortcut icon" href="{{{ asset('favicon.png') }}}">
            <link rel="stylesheet" href="{{ asset('css/style.css') }}">
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap-grid.css'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css'>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
        @show
    </head>
    <body>

        <section>
            @yield('content')
        </section>
 
        @section('script')
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
            <script src="{{ asset('js/index.js') }}"></script>
            <script src="{{ asset('js/moment.js') }}"></script>
            <script src="{{ asset('js/moment.distance.js') }}"></script>
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
        @show
    </body>
</html>