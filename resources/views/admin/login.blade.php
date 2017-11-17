<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PU | Login</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">

    <?php
    if (isset($data['styles'])) {
        foreach ($data['styles'] as $style) {
            echo '<link rel="stylesheet" href="/css/' . $style . '">';
        }
    }
    ?>

</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <img width="100px" height="100px" src="/assets/default_img/a.png"> <br>
        <b>Dinas PU</b> 
    </div>
    <div class="alert nonmodalalert redalert" id="error"></div>
    <div class="login-box-body">
        <p class="login-box-msg">Please login as an Administrator</p>
        <form action="" id="login">
            <div class="form-group has-feedback">
                <input type="text" name="adminid" id="email" class="form-control" placeholder="Admin Id">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4 col-xs-offset-8">
                    <button type="submit" id="btn-login" data-loading-text="<i class='fa fa-spinner fa-spin '></i>" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>
            </div>
        </form>
    </div>
</div> {{-- end of login-box--}}

</body>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>

<?php
if (isset($data['scripts'])) {
    foreach ($data['scripts'] as $script) {
        echo '<script src="/js/admin/' . $script . '"></script>';
    }
}
?>
</html>