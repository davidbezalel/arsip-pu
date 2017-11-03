<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> PU | {{ $data['title'] }} </title>

    {{-- make site responsive to the screen width --}}
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- load all required css --}}
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/datatable_custom.css">
    <link rel="stylesheet" href="/css/style.css">


    {{-- load all additional css --}}
    <?php
    if (isset($data['styles'])) {
        foreach ($data['styles'] as $style) {
            echo '<link rel="stylesheet" href="/css/' . $style . '">';
        }
    }
    ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <!-- Logo -->
        <a href="/admin/ppk" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>PU</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>PU</b> {{ App\Model\Admin::find(Auth::user()->id)->satker->name }}</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="navbar-nav nav">
                    {{-- log out  --}}
                    <li style="background-color: #367fa9; margin-left: 20px; font-weight: bold;"><a id="logout" href="">Log Out</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/assets/default_icons/administrator.png" class="img-circle user_photo_dashboard"
                         alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">

                {{-- PPK menu --}}
                <li class="<?php echo($data['controller'] == 'ppk' ? 'active' : ''); ?> treeview">
                    <a href="/admin/ppk">
                        <i class="fa"><span class="fa fa-users"></span></i> <span>PPK</span>
                    </a>
                </li>

                {{-- Paket menu --}}
                <li class="<?php echo($data['controller'] == 'paket' ? 'active' : ''); ?> treeview">
                    <a href="/admin/paket">
                        <i class="fa"><span class="fa fa-cubes"></span></i> <span>Paket</span>
                    </a>
                </li>

                {{-- Penunjukan PPK menu --}}

                {{-- Laporan menu --}}
                <li class="<?php echo($data['controller'] == 'report' ? 'active' : ''); ?> treeview">
                    <a href="/admin/laporan">
                        <i class="fa"><span class="fa fa-file-text"></span></i> <span>Laporan</span>
                    </a>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Developed by:</b> <a href="mailto:davidbezalel94@gmail.com">David Bezalel Laoli</a>
        </div>
        <strong>&copy; 2017 </strong>
        All rights reserved.
    </footer>


    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

{{-- load all required javascript --}}
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="/js/select2.min.js"></script>
<script src="/dist/js/app.min.js"></script>
<script src="/js/modal.js"></script>
<script src="/js/admin/admin.js"></script>

{{-- load all the additional javascript --}}
<?php
if (isset($data['scripts'])) {
    foreach ($data['scripts'] as $script) {
        echo '<script src="/js/admin/' . $script . '"></script>';
    }
}
?>
{{--<script src="/dist/js/pages/dashboard.js"></script>--}}
{{--<script src="/dist/js/demo.js"></script>--}}

</body>
</html>
