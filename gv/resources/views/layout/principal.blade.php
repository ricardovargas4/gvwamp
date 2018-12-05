<!DOCTYPE html>
<html>
<head>

    <link href="{{ asset('lib/materialize/dist/css/materialize.css') }} " rel="stylesheet">
    <link href="{{ asset('css/app.css') }} " rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }} " rel="stylesheet">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--<link href="/css/style.css?v={{$AppVersionCons}}" rel="stylesheet">-->
    <link href="{{ asset('css/style.css') }}?v={{$AppVersionCons}}" rel="stylesheet">
    <script src="{{asset('lib\jquery\dist\jquery.min.js')}}"></script>

    <title>Gestão à Vista</title>
</head>
<body>
  <header>
    @include('layout.navbar')
  </header>


  <div id="container">
    @yield('conteudo')
  </div>

</body>
    <script src="{{asset('js/Chart.js')}}"></script>
    <script src="{{asset('js/Chart.min.js')}}"></script> 
    <script src="{{asset('lib\materialize\dist\js\materialize.min.js')}}"></script>
    <script src="{{asset('js/init.js')}}"></script>
    <script src="{{ asset('js/validacao.js') }}?v={{$AppVersionCons}}"></script>

</html>
