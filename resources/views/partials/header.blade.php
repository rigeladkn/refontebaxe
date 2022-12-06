  <!-- Header
  ============================================= -->
  <header id="header">
      <div class="container">
          <div class="header-row">
              <div class="header-column justify-content-start">
                  <!-- Logo
          ============================= -->
                  <div class="logo"> <a class="d-flex" href="/" title="Baxe logo"><img
                              src="{{ asset('images/logo.png') }}" alt="Baxe" /></a> </div>
                  <!-- Logo end -->
                  <!-- Collapse Button
          ============================== -->
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#header-nav">
                      <span></span> <span></span> <span></span> </button><!-- Collapse Button end -->

                  <!-- Primary Navigation
          ============================== -->
          <nav class="primary-menu navbar navbar-expand-lg">
            <div id="header-nav" class="collapse navbar-collapse">
              <ul class="navbar-nav mr-auto">
                <li><a href="{{route("about")}}">A propos</a></li>
                <li><a href="{{route('contact')}}">Contact</a></li>
              
              </ul>
            </div>
          </nav>
          <!-- Primary Navigation end --> 
        </div>
        
        <div class="header-column justify-content-end">
          <!-- Login & Signup Link
          ============================== -->
          <nav class="login-signup navbar navbar-expand">
            <ul class="navbar-nav">
              @auth()
                <li><a href="javascript:void(0)"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit()"><i class="ti-power-off"></i> Logout</a></li>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="">
                      @csrf
                  </form>
              @else
              <li><a href="{{route('login')}}">Se connecter</a> </li>
                @if (Route::has('register'))
                  <li class="align-items-center h-auto ml-sm-3">
                    <a class="btn btn-primary d-none d-sm-block" href="{{route("signup")}}">S'inscrire</a>
                  </li>
                @endif

              <div class="header-column justify-content-end">
                  <!-- Login & Signup Link
          ============================== -->
                  <nav class="login-signup navbar navbar-expand">
                      <ul class="navbar-nav">
                          @auth()
                              <li><a href="javascript:void(0)" class="btn btn-outline-danger"
                                      onclick="event.preventDefault(); document.getElementById('logout-form').submit()"><i
                                          class="ti-power-off "></i>Logout</a></li>
                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="">
                                  @csrf
                              </form>
                          @else
                              <li><a href="{{ route('login') }}" class="btn btn-success">Se connecter</a> </li>
                              @if (Route::has('register'))
                                  <li class="align-items-center h-auto ml-sm-3">
                                      <a class="btn btn-primary d-none d-sm-block"
                                          href="{{ route('signup') }}">S'inscrire</a>
                                  </li>
                              @endif
                          @endauth
                      </ul>
                  </nav>
                  <!-- Login & Signup Link end -->
              </div>
          </div>
      </div>
  </header>
  <!-- Header End -->

  @yield('second-menu')
