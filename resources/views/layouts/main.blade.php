<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title : "Envision" }}</title>

    <!-- Bootstrap -->
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    @yield('css_assets')
  </head>
  <body>
    @include('layouts.header')
    @include('layouts.left-menu', ['fields' => Auth::user()->activeCompany()->fields])
    
    @yield('content')

    @include('layouts.footer')
  
    <script src="/js/app.config.seed.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!--[if IE 8]>
      <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
    <![endif]-->
    <!-- MAIN APP JS FILE -->
    <script src="/js/app.seed.js"></script>
    <script>
        $.ajaxSetup( { headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') } });
        $(function() {
            // Location Search Box
            $('#search_term').select2({
                placeholder: "Find Location",
                minimumInputLength: 2,
                ajax: {
                    url: "{{ action('CompanyController@getAllLocations')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        }
                    },
                    processResults: function(data, params) {
                        console.log(data);
                        return {
                            results: $.map(data, function(obj) {
                                return { id: obj.field_id, text: obj.name };
                            })
                        };
                    }
                },
            });
            $('#search_term').on('select2:select', function(e) {
                var id = e.params.data.id;
                console.log(id);
                window.location = '/locations?field_id=' + id + '&search_term=' + e.params.data.text;
            });
        });
    </script>
    @yield('footer_assets')
  </body>
  
</html>