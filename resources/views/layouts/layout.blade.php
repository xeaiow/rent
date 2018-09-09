<!DOCTYPE html>
<html>
    <head>
        <title>CYIM Rent - @yield('title')</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @section('style')
            <link rel="stylesheet" href="{{ asset('css/style.css') }}">
            <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap-grid.css'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
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
            <script>
                $(function() {
                    $('.tooltipped').tooltip();
                    $('select').formSelect();
                    $('.modal').modal({
                        dismissible: false,
                        opacity: .7,
                        onCloseEnd: function () {
                            $("#selectTimeTable").hide();
                            $("#room")[0].selectedIndex = 0;
                            $(".badge").remove();
                        }
                    });
                });
            </script>
        @show
    </body>
</html>