<?php 
	
	if($_SESSION['login_role'] != 'visitor')
	{
		header("location:index.php");
	}
	else
	{
 
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			login($_POST["email"], $_POST["password"]);
		}
?>

		<div class="col-12">
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>
			</script>
			<h1><center>Login</center></h1>
			<div class="forms">
				<form method="post" action="#" id="loginForm">
				  <div class="form-group">
				    <label for="email">Email address</label>
				    <input type="email" class="form-control" id="email" name="email" required>
				  </div>
				  <div class="form-group">
				    <label for="password">Password</label>
				    <input type="password" class="form-control" id="password" name="password" required>
				  </div>
				  <a href="index.php?page=forgot">Forgot Password</a>
				  <input type="hidden" name="captchaState" id="captchaState" value="0">
				  <div id="captcha" class="g-recaptcha" data-sitekey="6Lem3OIUAAAAAIBX8GaWtc9kmPTw80QPvQLoN-hL" data-callback="captchaReady"></div>
				  <br>
				</form>
				<button onclick="checkCaptcha()" class="btn btn-primary">Submit</button>
			</div>
			<script type="text/javascript">
				var captchaReady = function() {
					document.getElementById('captchaState').value = "1";
				};

				function checkCaptcha() {
					var state = document.getElementById('captchaState');
					if(state.value == "0")
					{
						alert("Please tick the I'm not a robot box.");
					}
					else if(state.value == "1")
					{
						// alert("submit");
						document.getElementById('loginForm').submit();
					}

				}
			</script>
		</div>

<?php
	}
?>