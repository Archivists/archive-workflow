<!DOCTYPE html>

<html lang="en">

<head id="Starter-Site">

    <meta charset="UTF-8">

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>
        @section('title')
            Administration
        @show
    </title>

    <!--  Mobile Viewport Fix -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"> -->

    <!-- This is the traditional favicon.
     - size: 16x16 or 32x32
     - transparency is OK
     - see wikipedia for info on browser support: http://mky.be/favicon/ -->
    <link rel="shortcut icon" href="{{{ asset('favicon.ico') }}}">

    <!-- CSS -->
    {{ HTML::style('css/bootstrap-fixed.css') }}
    {{ HTML::style('css/datatables.css') }}
    {{ HTML::style('css/style.css') }}

    <style>
    body {
        padding: 60px 0;
    }
    </style>

    @yield('styles')

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <!-- Container -->
      <div class="container">

      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <a class="navbar-brand" href="#">Archive Workflow</a>
      </div>
        <ul class="nav navbar-nav navbar-left">
          <li class="{{ (Request::is('/') ? ' active' : '') }}"><a href="{{{ URL::to('carriers') }}}"><span class="glyphicon glyphicon-home"></span> Home</a></li>
          
          @if (Auth::check() && Auth::user()->hasRole('admin'))
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Carrier Managment <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="{{{ URL::to('admin/categories') }}}">Categories</a></li>
                <li><a href="{{{ URL::to('admin/carrier-types') }}}">Carrier Types</a></li>
                <li><a href="{{{ URL::to('admin/status') }}}">Status Management</a></li>
              </ul>
            </li>
          @endif
        </ul>

        <ul class="nav navbar-nav navbar-right">
          @if (Auth::check())
            @if (Auth::user()->hasRole('admin'))
              <li class="dropdown {{ (Request::is('admin/user*|admin/role*') ? ' active' : '') }}">
              <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/user') }}}">
                Admin Tasks <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li {{ (Request::is('admin/user*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">Users</a></li>
                <li {{ (Request::is('role*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}">Roles</a></li>
              </ul>
             </li>
            @endif
            <li class="dropdown {{ (Request::is('user*') ? ' active' : '') }}">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('user') }}}">Logged in as {{{ Auth::user()->username }}} <span class="caret"></span>
            </a>
                <ul class="dropdown-menu">
                  <li {{ (Request::is('user*') ? ' class="active"' : '') }}>
                    <a href="{{{ URL::to('user') }}}">Settings</a>
                  </li>
              </ul>
            </li>
            <li><a href="{{{ URL::to('user/logout') }}}">Logout</a></li>
          @else
            <li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
          @endif
        </ul>
      
    </div><!-- /.container -->
    </nav>
    <!-- ./ navbar -->
        
    <div class="container">
        
        <!-- Content -->
        @yield('content')
        <!-- ./ content -->

        <!-- Footer -->
        <footer class="clearfix">
            @yield('footer')
        </footer>
        <!-- ./ Footer -->

    </div>
    <!-- ./ container -->

    <!-- Javascripts -->
    {{ HTML::script('js/jquery-1.10.2.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/jquery.dataTables.min.js') }}
    {{ HTML::script('js/datatables.fnReloadAjax.js') }}
    {{ HTML::script('js/datatables.js') }}

    @yield('scripts')

</body>

</html>