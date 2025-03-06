<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          {{-- <img src="{{url('adminlte/dist/img/user2.PNG')}}" class="user-image" alt="User Image"> --}}
          <span class="hidden-xs">{{auth()->user()->name}}</span>
        </a>
        <ul class="dropdown-menu">
          <li class="user-footer">
            <div class="row">
            <div class="align-center mb-2">
                <div class="col-auto">
                <form action="{{ route('change-password') }}" method="GET">
                    <button type="submit" class="btn btn-success" style="float:left; color:white">Ganti Password</button>
                </form>
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="float:right">Log Out</button>
                </form>
                </div>
            </div>
            </div>
            <div class="pull-right">

            </div>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
