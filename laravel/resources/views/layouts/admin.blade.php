<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="x icon" type="img/png" href="/images/CvSU-logo-16x16.webp">
        <title>Admin Dashboard</title>
        <!-- Bootstrap CSS v5.2.1 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
        <!-- Sweet Alert 2 -->
        <link rel="stylesheet" href="{{asset('plugins/sweetalert2/sweetalert2.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
        <!-- Sweet Alert 2 -->
        <link rel="stylesheet" href="{{asset('plugins/sweetalert2/sweetalert2.min.css')}}">
        <!-- css -->
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>

    <body>
        <header>

            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Sidebar --}}
            @include('partials.sidebar')
        
        </header>
        <main>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                {{-- @yield('main-content-header') --}}
                <!-- /.content-header -->

                <!-- Main content -->
                @yield('main-content')
                <!-- /.content -->
                </div>
            <!-- /.content-wrapper -->
        </main>
        <footer>
            
        </footer>
        {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script> --}}
    </body>
        <!-- REQUIRED SCRIPTS -->
            <!-- jQuery -->
            <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
            <!-- Bootstrap 4 -->
            <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
            <!-- Sweet Alert 2 -->
            <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
            <!-- AdminLTE App -->
            <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
            <!-- Sweet Alert 2 -->
            <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
            @yield('script')
</html>
