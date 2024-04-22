<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <!-- jQuery -->
        <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>\
        <!-- DataTables JavaScript -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js"></script>

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

        <!-- Buttons extension CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">

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
            @include('partials.footer')
        </footer>
    </body>
        <!-- REQUIRED SCRIPTS -->
            
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