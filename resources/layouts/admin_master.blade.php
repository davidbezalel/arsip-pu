<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> RymaHousing | {{ $data['title'] }} </title>

    {{-- make site responsive to the screen width --}}
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- load all required css --}}
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
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
        <a href="/dashboard" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>AG</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Ryma</b>Housing</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="navbar-nav nav">

                    <!-- transaction menu  -->
                    <li id="transactionmenu" class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-get-pocket"></i>
                            <span id="transactionnotificationcount" class="label label-danger"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header" id="transactionnotificationheader" style="font-size: 14px !important;"></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul id="transactionlist" class="menu">
                                </ul>
                            </li>
                        </ul>
                    </li>

                    {{-- log out  --}}
                    <li style="background-color: #23527c; margin-left: 20px;"><a href="/admin/logout">Log Out</a></li>
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
                {{-- dashboard menu --}}
                <li class="<?php echo ($data['controller'] == 'dashboard' ? 'active' : ''); ?> treeview">
                    <a href="/admin">
                        <i class="fa"><span class="glyphicon glyphicon-dashboard"></span></i> <span>Dashboard</span>
                    </a>
                </li>
                {{-- player menu --}}
                <li class="<?php echo ($data['controller'] == 'player' ? 'active' : ''); ?> treeview">
                    <a href="/admin/player">
                        <i class="fa fa-users"></i> <span>Player</span>
                    </a>
                </li>
                {{-- game menu --}}
                <li class="<?php echo ($data['controller'] == 'game' ? 'active' : ''); ?> treeview">
                    <a href="/admin/game">
                        <i class="fa fa-gamepad"></i> <span>Game</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo(isset($data['function']) && $data['function'] == 'game' ? 'active' : ''); ?>"><a href="/admin/game"><i class="fa fa-circle-o"></i>List</a></li>
                        <li class="<?php echo(isset($data['function']) && $data['function'] == 'gameplay' ? 'active' : ''); ?>"><a href="/admin/game/gameplay"><i class="fa fa-circle-o"></i>Game Play</a></li>
                        <li class="<?php echo(isset($data['function']) && $data['function'] == 'winningnumber' ? 'active' : ''); ?>"><a href="/admin/game/winningnumber"><i class="fa fa-circle-o"></i>Winning Number</a></li>
                    </ul>
                </li>
                {{-- bank menu --}}
                <li class="<?php echo ($data['controller'] == 'bank' ? 'active' : ''); ?> treeview">
                    <a href="/admin/bank">
                        <i class="fa"><span class="fa fa-bank"></span></i><span>Bank</span>
                    </a>
                </li>
                {{-- transaction menu --}}
                <li class="<?php echo ($data['controller'] == 'transaction' ? 'active' : ''); ?> treeview">
                    <a href="/admin/transaction">
                        <i class="fa"><span class="glyphicon glyphicon-transfer"></span></i><span>Transaction</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo(isset($data['function']) && $data['function'] == 'transfer' ? 'active' : ''); ?>"><a href="/admin/transaction"><i class="fa fa-money"></i>Transfer</a></li>
                        <li class="<?php echo(isset($data['function']) && $data['function'] == 'deposit' ? 'active' : ''); ?>"><a href="/admin/deposit"><i class="fa fa-creative-commons"></i>Deposit</a></li>
                    </ul>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> beta
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
