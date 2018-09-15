<!DOCTYPE html>
<html>
    <head>
        <title>中原資管教室預約系統後台</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=0">
        @section('style')
            <link rel="shortcut icon" href="{{{ asset('favicon.png') }}}">
            <link rel="stylesheet" href="{{ asset('css/style.css') }}">
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css'>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
        @show
    </head>
    <body id="app">

        <section>
            @yield('content')
        </section>

        @section('script')
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="{{ asset('js/admin.js') }}"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script>
                $('.modal').modal({
                    opacity: .3,
                });
            </script>
        @show
    </body>
</html>