<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PU | Register</title>
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
<body class="hold-transition register-page">

<div class="register-box">
    <div class="register-logo">
        <b>Dinas PU</b> Wilayah I
    </div>
    <div class="alert nonmodalalert redalert" id="error"></div>
    <div class="register-box-body">
        <p class="login-box-msg">Please Register an Administrator</p>

        <form action="" id="register">
            <div class="form-group has-feedback">
                <input type="text" name="name" id="name" class="form-control" placeholder="Full Name">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" name="adminid" id="adminid" class="form-control" placeholder="Alias">
                <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="repassword" id="repassword" class="form-control"
                       placeholder="Re-Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-offset-8 col-xs-4">
                    <button type="submit" id="btn-register"
                            data-loading-text="<i class='fa fa-spinner fa-spin '></i>"
                            class="btn btn-block btn-primary btn-flat">
                        Register
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> {{-- end of register-box--}}

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