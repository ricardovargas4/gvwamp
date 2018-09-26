<!DOCTYPE html>
<html>
<head>
    <?php $AppVersion = "1.0.0.8"; ?>

    <link href="{{ asset('lib/materialize/dist/css/materialize.css') }} " rel="stylesheet">
    <link href="{{ asset('css/app.css') }} " rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }} " rel="stylesheet">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--<link href="/css/style.css?v=<?php echo $AppVersion; ?>" rel="stylesheet">-->
    <link href="{{ asset('css/style.css') }}?v={{$AppVersion}}" rel="stylesheet">

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
<!--
<div class = "footerteste">
      <footer class="page-footer light-green <accent-2></accent-2>" >
        <div class="container">
          <div class="row">
            <div class="col l6 s12">
              <h5 class="white-text">Confederação Sicredi</h5>
              <p class="grey-text text-lighten-4">Gestão à Vista.</p>
              <p>
                <a href="#"><i class="white-text mdi mdi-facebook mdi-24px"></i></a>
                <a href="#"><i class="white-text mdi mdi-twitter mdi-24px"></i></a>
                <a href="#"><i class="white-text mdi mdi-youtube-play mdi-24px"></i></a>
              </p>
            </div>
            <div class="col l4 offset-l2 s12">
              <h5 class="white-text">Links</h5>
              <ul>
                <li><a class="grey-text text-lighten-3" href="{{ url('/home') }}">Home</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="footer-copyright">
          <div class="container">
          © 2017 Copyright Text
          <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
-->

    <script src="{{asset('js/Chart.js')}}"></script>
    <script src="{{asset('js/Chart.min.js')}}"></script> 
    <script src="{{asset('lib\jquery\dist\jquery.min.js')}}"></script>
    <script src="{{asset('lib\materialize\dist\js\materialize.min.js')}}"></script>
    <script src="{{asset('js/init.js')}}"></script>
    <script src="{{ asset('js/validacao.js') }}?v={{$AppVersion}}"></script>
    <script src="{{ asset('js/graficoTempo.js') }}?v={{$AppVersion}}"></script>

</html>
