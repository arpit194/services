<?php 
	
	if($_SESSION['login_role'] != 'visitor')
	{
		header("location:index.php");
	}
	else
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			register($_POST["name"], $_POST["email"], $_POST["password"], $_POST["pno"],$_POST["userType"],$_POST["userType"]);
		}
?>

<div class="col-12">
    <h1>
        <center>Register</center>
    </h1>
    <div class="forms">
        <form method="post" action="#">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="name">Phone No.</label>
                <input type="tel" class="form-control" id="pno" name="pno" required>
            </div>
            <div class="form-group">
                <label for="userType">User Type</label>
                <select class="form-control" id="userType" name="userType">
                    <option value="customer">Customer</option>
                    <option value="staff">Service Provider</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
</div>

<?php
	}
?>