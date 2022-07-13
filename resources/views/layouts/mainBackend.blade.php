<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <title>{{__('msg.Dashboard')}} @yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/bootstrap-datetimepicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('/assets-backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  {{--<link rel="stylesheet" href="/assets-backend/css/bootstrap.css">--}}
  <link rel="stylesheet" href="{{asset('/assets-backend/css/adminlte.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('/assets-backend/css/custom.css')}}">
  @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
    <form class="form-inline ml-30">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

      <div><a href="{{route('site.index')}}" target="_blank">Saytga o'tish</a></div>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          {{App::currentLocale()}}  <i class="fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 200px;max-width: 200px;">
          <div class="dropdown-divider"></div>
          <a href="{{route('fswitchLocale','uz')}}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> {{ __('Uz') }}
          </a>
          <span class="float-right text-muted text-sm"></span>

          <div class="dropdown-divider"></div>
          <a href="{{route('fswitchLocale','ru')}}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> {{ __('Ru') }}
          </a>
          <span class="float-right text-muted text-sm"></span>
          <div class="dropdown-divider"></div>
          <a href="{{route('fswitchLocale','en')}}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> {{ __('En') }}
          </a>
          <span class="float-right text-muted text-sm"></span>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            {{ Auth::user()->name }}  <i class="fas fa-angle-down"></i>
         </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 200px;max-width: 200px;">
           <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> {{ __('Settings') }}
          </a>
          <span class="float-right text-muted text-sm"></span>

          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}" 
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> 
            {{ __('Logout') }}
          </a>
          <span class="float-right text-muted text-sm"> 
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </span>

        </div>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="">
      <a href="{{route('admin.index')}}" class="brand-link">
        <img src="{{asset('assets-backend/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
      </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item ">
            <a href="{{route('admin.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                {{__('msg.Home')}}
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="{{route('searchbar.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Izlash
              </p>
            </a>
          </li>
          @role('super admin')
          <li class="nav-item ">
            <a href="{{route('borowwing.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Kitob berish
              </p>
            </a>
          </li>
          @endrole
          <li class="nav-item ">
            <a href="{{route('borowwing-filter.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Monitoring
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="{{route('borowwing-no-return.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Topshirilmagan kitoblar
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="{{route('borowwing-return.index')}}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Topshirilgan kitoblar
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Kutubxona
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item ">
                <a href="{{route('student.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Talabalar</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('teacher.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>O'qituvchilar</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('worker.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ishchi xodimlar</p>
                </a>
              </li>
              @role('super admin')
              <li class="nav-item ">
                <a href="{{route('course.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Foydalanuvchi kategoriyasi</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('book.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kitoblar</p>
                </a>
              </li>
             
              <li class="nav-item ">
                <a href="{{route('book-category.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kitob kategoriyasi</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('elektron-book.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Elektron kitob</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('menu.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menu</p>
                </a>
              </li>
              @endrole
              
            </ul>
          </li>


          @role('super admin')

          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                {{__('msg.Settings')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ">
                <a href="{{route('admin-user.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Admin users</p>
                </a>
              </li>
              
              <li class="nav-item ">
                <a href="{{route('user.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              
              <li class="nav-item ">
                <a href="{{route('role.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('permission.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permissions</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="{{route('role-has-permissions.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Role has permissions</p>
                </a>
              </li>
              
              <li class="nav-item ">
                <a href="{{route('personal-access-token.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Personal access token</p>
                </a>
              </li>

            </ul>
          </li>

          @endrole

        </ul>       
      </nav>
    </div>
  </aside>

  <div class="content-wrapper"  style="">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          
          
          
            @yield('content')
          


          </div>
        </div>
      </div>
    </section>
  </div>



  <footer class="main-footer" style="">
    <strong>Copyright &copy; 2014-2020 <a href="#">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0-rc
    </div>
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>



<script src="{{asset('/assets-backend/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('/assets-backend/js/bootstrap.js')}}"></script>
{{-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> --}}
<script src="{{asset('/assets-backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('/assets-backend/js/demo.js')}}"></script>
 {{--<script src="{{asset('assets-backend/js/pages/dashboard.js')}}"></script> --}}
<script src="{{asset('/assets-backend/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('/assets-backend/plugins/bootstrap-datetimepicker.js')}}"></script>
<script src="{{asset('/assets-backend/js/custom2.js')}}"></script>

<script src="{{asset('/assets-backend/js/adminlte.js')}}"></script>
<script src="{{asset('/assets-backend/js/custom.js')}}"></script>

@yield('scripts')
<script>
    $('.nav-item a').each(function(){
        let browserlocation = window.location.protocol + '//' + window.location.host + window.location.pathname;
        let alink = this.href;
        if (browserlocation == alink) {
            // $(this).parent().addClass('menu-open');
            $(this).addClass('active');
            $(this).parent().addClass('nav-item-menu-active');
            $('.nav-item-menu-active').parent().addClass('nav-menu-active');
            $('.nav-menu-active').parent().addClass('nav-nav-item-menu-active menu-open');
            $('.nav-nav-item-menu-active>a').addClass('active');

        }
    });

</script>
</body>
</html>













