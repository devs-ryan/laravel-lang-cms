<?php

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
   
    <!-- Bootstrap -->
    <script src="./includes/jquery.min.js"></script>
    <link href="./includes/bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="./includes/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="./includes/fontawesome-free-5.12.0-web/css/all.min.css">


	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="./css/login.css">
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Sign In</h3>
				<div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-github"></i></span>
				</div>
			</div>
			<div class="card-body">
				<form>

					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" placeholder="password">
					</div>
					<div class="form-group">
						<input type="submit" value="Login" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center">
					<a href="#">Forgot your password?</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>