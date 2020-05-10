<?php

include './app/autoload.php';
$support_email = env('SUPPORT_EMAIL');

?>

<!DOCTYPE html>
<html>

<head>
    <title>Lang CMS - Login</title>

    <!-- Bootstrap -->
    <script src="./includes/jquery.min.js"></script>
    <link href="./includes/bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="./includes/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

    <!--Fontawesome-->
    <link rel="stylesheet" href="./includes/fontawesome-free-5.12.0-web/css/all.min.css">
    
    <!--Sweet Alert-->
    <script src="./includes/sweetalert2.all.min.js"></script>

    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="./css/login.css">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Lang CMS - Login</h3>
                    <div class="d-flex justify-content-end social_icon">
                        <span>
                            <a class="no-decor" target="_blank" href="https://github.com/raysirsharp/laravel-lang-cms">
                                <i class="fab fa-github"></i>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-white">Please enter the administrative password provided to you by your web designer. </p>
                    <form method="post" action="./file_index.php">

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input name="password" required type="password" class="form-control" placeholder="password">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Login" class="btn float-right login_btn">
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        <a onclick="showSupport()" href="JavaScript:Void(0);">Forgot your password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Messages --> 
    <?php include './app/msg.php'; ?>
    
    <!-- SCRIPTS --> 
    <script>
        function showSupport() {
            Swal.fire(
                'Need Support?',
                'Contact <?php 
                    if ($support_email)
                        echo $support_email;
                    else
                        echo "your system admin";
                ?> for help, or password reset.',
                'question'
            )
        }
    </script>
    
</body>

</html>