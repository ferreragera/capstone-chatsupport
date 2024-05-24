<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark- elevation-4">
  <!-- Brand Logo -->
  <div class="brand-link d-flex justify-content-between align-items-center">
    <a class="brand-link" href="#">
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
    <nav class="mt-2" id="nav_Div">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link" data-url="{{ route('dashboard') }}">
                <i class="fas fa-file-alt"></i>
                <p>Datasets</p> 
            </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('train') }}" class="nav-link" data-url="{{ route('train') }}">
            <i class="fas fa-cogs"></i>
              <p>Train</p>
          </a>
      </li>
        <li class="nav-item">
            <a href="{{ route('reports') }}" class="nav-link" data-url="{{ route('reports') }}">
                <i class="fas fa-chart-line"></i>
                <p>Reports</p>
            </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('archivePage') }}" class="nav-link" data-url="{{ route('archivePage') }}">
              <i class="fas fa-stream"></i>
              <p>Archive</p>
          </a>
        </li>
        <li class="nav-header text-uppercase">External Links</li>
        <li class="nav-item">
            <a href="https://apps.cvsu.edu.ph/admission/sign-in/" class="nav-link" target="_blank" data-url="https://apps.cvsu.edu.ph/admission/sign-in/">
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
  document.addEventListener('DOMContentLoaded', function() {
      var header = document.getElementById("nav_Div");
      var btns = header.getElementsByClassName("nav-link");
      var currentUrl = window.location.href;

      for (var i = 0; i < btns.length; i++) {
          if (btns[i].getAttribute('data-url') === currentUrl) {
              btns[i].classList.add('active');
          }
          btns[i].addEventListener("click", function() {
              var current = document.getElementsByClassName("active");
              if (current.length > 0) {
                  current[0].className = current[0].className.replace(" active", "");
              }
              this.className += " active";
          });
      }
  });
</script>
@endsection
