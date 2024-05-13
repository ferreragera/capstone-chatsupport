<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark- elevation-4">
    <!-- Brand Logo -->
    <div class="brand-link d-flex justify-content-between align-items-center">
      <a class="brand-link" href="index3.html">
        <img src="{{ asset('images/CVSU-logo-trans.png')}}" alt="CVSU Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">Admin</span>
      </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ $googleUserInfo->picture }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ $googleUserInfo->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
          <li class="nav-item">
              <a href="{{ route('dashboard') }}" class="nav-link active">
                  <i class="fas fa-file-alt"></i>
                  <p>Datasets</p> 
              </a>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ route('train') }}" class="nav-link">
                <i class="fas fa-stream"></i>
                <p>Train</p>
            </a>
        </li> --}}
          <li class="nav-item">
              <a href="{{ route('reports') }}" class="nav-link">
                  <i class="fas fa-chart-line"></i>
                  <p>Reports</p>
              </a>
          </li>
          <li class="nav-header text-uppercase">External Links</li>
          <li class="nav-item">
              <a href="https://apps.cvsu.edu.ph/admission/sign-in/" class="nav-link" target="_blank">
                  <i class="nav-icon fas fa-th"></i>
                  <p>CvSU Online Admission</p>
              </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  @section('script')
  <script>
    $(document).ready(function() {
      $(".nav .nav-link").on("click", function(){
        $(".nav").find(".active").removeClass("active");
        $(this).closest('.nav-item').addClass("active");
      });
    });
  </script>
  @endsection
  