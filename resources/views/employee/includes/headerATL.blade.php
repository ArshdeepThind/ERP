<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('/employee') }}" class="logo">
    <span class="logo-lg">ERP SYSTEM</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
 

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        @if(Auth::user()->pic=='')
          <img src="{{ asset('storage/avatar.png') }}" class="user-image" alt="User Image">
        @else
          <img src="{{ asset('storage/employee/'.Auth::user()->id.'/'.Auth::user()->pic) }}" class="user-image" alt="User Image">
        @endif

          
        <span class="hidden-xs">{{ ucwords(Auth::user()->name) }}</span>
        </a>
        <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              @if(Auth::user()->pic=='')
                <img src="{{ asset('storage/avatar.png') }}" class="img-circle" alt="User Image">
              @else
                <img src="{{ asset('storage/employee/'.Auth::user()->id.'/'.Auth::user()->pic) }}" class="img-circle" alt="User Image">
              @endif
              <p>
                {{ ucwords(Auth::user()->first_name) }}
                <small>{{ Auth::user()->email }}</small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ url('/employee/profile') }}" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <a href="{{ url('/employee/logout') }}" 
                  onclick="event.preventDefault();document.getElementById('logout-form').submit();" 
                  class="btn btn-default btn-flat">Sign out</a>
                  <form id="logout-form" action="{{ url('/employee/logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>