<?php
  require_once("application/session.php");
  require_once("application/protect.user.php");
  $user = new user();
  $auth_user = new USER();
  $user_id = $_SESSION['user_session'];
  $stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
  $stmt->execute(array(":user_id"=>$user_id));
  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
  $pr_id = $userRow['pr_id'];
  $redirect_login = config::get('redirect_login');

  if(!$auth_user->is_loggedin())
{
	$auth_user->redirect('login.php?return=changepass.php');
}

if(isset($_POST['btn-changepass']))
{
	$password = strip_tags($_POST['txt_password']);
	$passwordconfirm = strip_tags($_POST['txt_password_confirm']);
//
	if($password!=$passwordconfirm) {
		$error[] = "Password doesn't match !";
	}
	else if($password=="")	{
		$error[] = "provide password !";
	}
	else if(strlen($password) < 6){
		$error[] = "Password must be atleast 6 characters";	
	}
	else
		{
			try
			{
		if($user->changepass($password, $pr_id)){	
					$user->redirect('message.php?90819948564782345789056748PasswordChanged857432');
				}
		
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	}
?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Cambio Password</title>
      <link rel="stylesheet" href="assets/css/login-signup.css">
</head>
  <body>
  <body>
<h1><span style="color: #ffffff; font-size:25px;">Cambio Password</span></h1>		
<br>
<div class="container" id="container">
	<div class="form-container sign-in-container">
	
	
		<form class="form-signin" method="post" id="login-form">
		<form action="">
			<h3> Cambio Password</h3>

<?php
	if(isset($error))
	{
	 	foreach($error as $error)
	{
?>
            <div id="error">
            <h3>&nbsp; <?php echo $error; ?> !</h3> 
            <script type="text/javascript">function timedMsg(){var t=setTimeout("document.getElementById('error').style.display='none';",4000);}</script>
			<script language="JavaScript" type="text/javascript">timedMsg()</script>
            </div>

    <?php
	}
	}
	?>

	<?php
		{
		if(config::get('change_pass'))
		{
			?>
			<div class="form-group">
				<input type="password" class="form-control" name="txt_password" placeholder="Nuova Password" required="" id="username" />
			</div>

			<div class="form-group">
				<input type="password" class="form-control" name="txt_password_confirm" placeholder="Conferma Nuova Password" required="" id="password" />
			</div>

			<div class="form-group">
				<input type="submit" name="btn-changepass" value="Cambia Password" />
			</div><br><br>
			<div class="form-group">
			<a href="<?php echo $redirect_login ?>"><b>Cancella il cambio</b></a>
			</div></div>
			<?php
		}
		else
			header("location: message.php?34585474839057488936799350PassChangeDisabled985484");
		}
	      ?>
	
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
					<h1>Ciao!</h1>
				<p>Aggiorna la tua password</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Ciao!</h1>
				<p>Da questa pagina puoi aggiornare la tua password! Ti Raccomandiamo di utilizzare password lunghe almeno 8 caratteri con maiuscole, minuscole e numeri.</p>
			</div>
		</div>
	</div>


	</section><!-- content -->
</div><!-- container -->
<script src="js/index.js"></script>

</body>
</html>


<style type="text/css">

@import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

* {
	box-sizing: border-box;
}

body {
	background: #d5d5d5;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	font-family: 'Montserrat', sans-serif;
	height: 100vh;
	margin: -20px 0 50px;
}

h1 {
	font-weight: bold;
	margin: 0;
}

h2 {
	text-align: center;
}

p {
	font-size: 14px;
	font-weight: 100;
	line-height: 20px;
	letter-spacing: 0.5px;
	margin: 20px 0 30px;
}

span {
	font-size: 12px;
}

a {
	color: #333;
	font-size: 14px;
	text-decoration: none;
	margin: 15px 0;
}

button {
	border-radius: 20px;
	border: 1px solid #FF4B2B;
	background-color: #FF4B2B;
	color: #FFFFFF;
	font-size: 12px;
	font-weight: bold;
	padding: 12px 45px;
	letter-spacing: 1px;
	text-transform: uppercase;
	transition: transform 80ms ease-in;
}

button:active {
	transform: scale(0.95);
}

button:focus {
	outline: none;
}

button.ghost {
	background-color: transparent;
	border-color: #FFFFFF;
}

form {
	background-color: #FFFFFF;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 50px;
	height: 100%;
	text-align: center;
}

input {
	background-color: #eee;
	border: none;
	padding: 12px 15px;
	margin: 8px 0;
	width: 100%;
}

.container {
	background-color: #fff;
	border-radius: 10px;
  	box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
			0 10px 10px rgba(0,0,0,0.22);
	position: relative;
	overflow: hidden;
	width: 768px;
	max-width: 100%;
	min-height: 480px;
}

.form-container {
	position: absolute;
	top: 0;
	height: 100%;
	transition: all 0.6s ease-in-out;
}

.sign-in-container {
	left: 0;
	width: 50%;
	z-index: 2;
}

.container.right-panel-active .sign-in-container {
	transform: translateX(100%);
}

.sign-up-container {
	left: 0;
	width: 50%;
	opacity: 0;
	z-index: 1;
}

.container.right-panel-active .sign-up-container {
	transform: translateX(100%);
	opacity: 1;
	z-index: 5;
	animation: show 0.6s;
}

@keyframes show {
	0%, 49.99% {
		opacity: 0;
		z-index: 1;
	}
	
	50%, 100% {
		opacity: 1;
		z-index: 5;
	}
}

.overlay-container {
	position: absolute;
	top: 0;
	left: 50%;
	width: 50%;
	height: 100%;
	overflow: hidden;
	transition: transform 0.6s ease-in-out;
	z-index: 100;
}

.container.right-panel-active .overlay-container{
	transform: translateX(-100%);
}

.overlay {
	background: #FF416C;
	background: -webkit-linear-gradient(to right, #2bf0ff, #4150ff);
	background: linear-gradient(to right, #2bf0ff, #4150ff);
	background-repeat: no-repeat;
	background-size: cover;
	background-position: 0 0;
	color: #FFFFFF;
	position: relative;
	left: -100%;
	height: 100%;
	width: 200%;
  	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.container.right-panel-active .overlay {
  	transform: translateX(50%);
}

.overlay-panel {
	position: absolute;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 40px;
	text-align: center;
	top: 0;
	height: 100%;
	width: 50%;
	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.overlay-left {
	transform: translateX(-20%);
}

.container.right-panel-active .overlay-left {
	transform: translateX(0);
}

.overlay-right {
	right: 0;
	transform: translateX(0);
}

.container.right-panel-active .overlay-right {
	transform: translateX(20%);
}

.social-container {
	margin: 20px 0;
}

.social-container a {
	border: 1px solid #DDDDDD;
	border-radius: 50%;
	display: inline-flex;
	justify-content: center;
	align-items: center;
	margin: 0 5px;
	height: 40px;
	width: 40px;
}

footer {
    background-color: #222;
    color: #fff;
    font-size: 14px;
    bottom: 0;
    position: fixed;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 999;
}

footer p {
    margin: 10px 0;
}

footer i {
    color: red;
}

footer a {
    color: #3c97bf;
    text-decoration: none;
}
</style>
