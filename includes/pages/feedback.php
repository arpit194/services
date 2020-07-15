<?php 
	
	if($_SESSION['login_role'] != 'customer')
	{
		header("location:index.php");
	}
	else
	{
?>

<div class="col-12">
    <h1>
        <center>Feedback</center>
    </h1>
    <div class="forms">
        <form method="post" action="adminOps.php" id="loginForm">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" name="message" id="message" rows="5"></textarea>
            </div>
            <input type="hidden" name="operation" value="feedback">
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<?php
	}
?>