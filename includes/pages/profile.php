<?php 
	
	if($_SESSION['login_role'] == 'visitor')
	{
		header("location:index.php");
	}
	else
	{
?>

<div class="col-12">
    <h1>
        <center>Profile</center>
    </h1>
    <div class="container">
        <div class="row">
            <div onclick="changeDiv('profileForm','profile')" class="col-1 profileButton" id="profile"
                style="background-color: #0984e3;">
                <center>Profile</center>
            </div>
            <div onclick="changeDiv('changePswdForm', 'changePswd')" class="col-2 profileButton" id="changePswd">
                <center>Change Password</center>
            </div>
        </div>
        <div class="row">
            <div class="col-12 profileForm" id="profileForm">
                <h2>Update Profile</h2>
                <?php $user = getUserDetail($_SESSION['login_id']); ?>
                <form method="post" action="adminOps.php">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo $user['name'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $user['emailid'] ?>"
                            name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone No.</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?php echo $user['phone'] ?>" required>
                    </div>
                    <input type="hidden" name="role" value="<?php echo $user['role'] ?>">
                    <input type="hidden" name="operation" value="updateProfile">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </form>
            </div>
            <div class="col-12 profileForm" id="changePswdForm" style="display: none">
                <h2>Update Password</h2>
                <?php $user = getUserDetail($_SESSION['login_id']); ?>
                <form method="post" action="adminOps.php">
                    <div class="form-group">
                        <label for="oldPswd">Old Password</label>
                        <input type="password" class="form-control" id="oldPswd" name="oldPswd" required>
                    </div>

                    <div class="form-group">
                        <label for="newPswd">New Password</label>
                        <input type="password" class="form-control" id="newPswd" name="newPswd" required>
                    </div>

                    <div class="form-group">
                        <label for="cnfPswd">Confirm Password</label>
                        <input type="password" class="form-control" id="cnfPswd" name="cnfPswd" required>
                    </div>
                    <input type="hidden" name="operation" value="changePassword">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function changeDiv(divId, btnId) {
    document.getElementById('profileForm').style.display = "none";
    document.getElementById('changePswdForm').style.display = "none";
    document.getElementById(divId).style.display = "block";

    document.getElementById('profile').style.backgroundColor = 'white';
    document.getElementById('changePswd').style.backgroundColor = 'white';
    document.getElementById(btnId).style.backgroundColor = '#0984e3';
}
</script>

<?php
	}
?>