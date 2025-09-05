<?php
require_once("application/protect.user.php");
$auth_user = new USER();

if(isset($_POST['btn-login']))
{	
	header("location: login.php");
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Message | PhpPasswordProtect</title>
      <link rel="stylesheet" href="assets/css/login-signup.css">
      <link rel="stylesheet" href="assets/css/login-signup1.css">
</head>
<body>
<div class="signin-form">
<div class="container">
	<?php
		if(isset($_GET['8338292374938737483472923Success465443']))
		{
			?>

			<section id="content">
		        <form class="form-signin" method="post" id="login-form">
					<form action="">
						<h2>User Registered</h2>
							<h5>Registration successfull, You can now login</h5>
								You will be redirected to login page in <span id="counter">4</span> second(s).</p>
						<div class="form-group" style="padding-left:110px;">
						<input type="submit" value="login" name="btn-login"/>
						</div>
					</form>
			</section>

	<?php
		}
		elseif(isset($_GET['88673834958894930356536786SignupDisabled455843']))
		{
			?> 
		        <section id="content">
		        	<form class="form-signin" method="post" id="login-form">
					<form action="">
						<h2>User Registration Disabled by Admin</h2>
						<div class="form-group" style="padding-left:110px;">
						<input type="submit" value="login" name="btn-login"/>
						</div>
					</form>
				</section>
    <?php
        }
        elseif(isset($_GET['90819948564782345789056748PasswordChanged857432']))
		{	
		?> 
		        <section id="content">
		        	<form class="form-signin" method="post" id="login-form">
					<form action="">
						<h2>Password Changed Successfully</h2>
						You will be redirected to home page in <span id="counter">3</span> second(s).</p>
						<div class="form-group" style="padding-left:110px;">
						<input type="submit" value="Home" name="btn-login"/>
						</div>
					</form>
				</section>
    <?php
        }

        elseif(isset($_GET['34585474839057488936799350PassChangeDisabled985484']))

		{
			?> 
				<section id="content">
		        	<form class="form-signin" method="post" id="login-form">
					<form action="">
						<h2>Password Change Disabled by Admin</h2>
						You will be redirected to home page in <span id="counter">4</span> second(s).</p>
						<div class="form-group" style="padding-left:110px;">
						<input type="submit" value="Home" name="btn-login"/>
						</div>
					</form>
				</section>

    <?php
        }
		else
		{
			?>
				<center>
				<h2>Nothing to show here</h2></center>
				<?php header("location: login.php") ?>
					</div>
				</form>
			<?php
		}
	?>
</div>
</div>
</body>
	<script src="js/index.js"></script>
	<script type="text/javascript">
		function countdown() {
	    var i = document.getElementById('counter');
	    if (parseInt(i.innerHTML)<=1) {
	        location.href = 'login.php';
	    }
	    i.innerHTML = parseInt(i.innerHTML)-1;
		}
		setInterval(function(){ countdown(); },800);
	</script>
</body>
</html>
