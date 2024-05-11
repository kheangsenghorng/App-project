<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Shop :: Administrative Panel</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{asset('admin-assets/plugins/summernote/summernote.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/custom.css')}}">
   <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <div class="navbar-nav pl-2">
          </div>
        
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
              <img src="{{ asset('admin-assets/img/avatar5.png') }}" class='img-circle elevation-2' width="40" height="40" alt="">
            </a>
          <div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Senghorng
  </button>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="dropdownMenuButton">
      
     <h4 class="h4 mb-0"><strong>Admin</strong></h4>
    <div class="mb-3">Admin@gamil.com</div>
     <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#">
      <i class="fas fa-user-cog mr-2"></i> Settings
    </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#">
      <i class="fas fa-lock mr-2"></i> Change Password
    </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item text-danger" href="#">
      <i class="fas fa-sign-out-alt mr-2"></i> Logout
    </a>
  </div>
</div>

          </li>
        </ul>
      </nav>
      @include('admin.layouts.sidebar')

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
         
              @yield('content')
			</div>
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				
				<strong>Copyright &copy; 2014-2022 AmazingShop All rights reserved.
			</footer>
			
		</div>
		<!-- ./wrapper -->
		<!-- jQuery -->
		<script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
		<!-- Bootstrap 4 -->
		<script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>

		<script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
		<script src="{{ asset('admin-assets/plugins/summernote/summernote.min.js') }}"></script>

		<!-- AdminLTE for demo purposes -->
  
		<script src="{{ asset('admin-assets/js/demo.js') }}"></script>

		
    <script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    
    $(document).ready(function() {
        $(".summernote").summernote({
           height:250
        });
    });
    
</script>


        @yield('customJs')
	</body>
</html>