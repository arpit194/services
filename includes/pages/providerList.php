<?php 
	
	if($_SESSION['login_role'] != 'admin')
	{
		header("location:index.php");
	}
	else
	{
?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="deleteId">
            <div class="modal-body">
                This is a permanent operation. Are you sure about deleting?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button onclick="deleteFrm()" type="button" class="btn btn-primary">Confirm Deletion</button>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <?php 
				$limit = 5;
				$pageNo = 1;
				if($_SERVER["REQUEST_METHOD"] == "POST")
				{
					if($_POST['operation'] == "createUser")
					{
			?>
    <h2>Create new User</h2>
    <div class="forms">
        <form method="post" action="adminOps.php">
            <div class="form-group">
                <label for="userType">User Type</label>
                <select class="form-control" id="userType" name="role">
                    <option>admin</option>
                    <option>staff</option>
                    <option>customer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option>enabled</option>
                    <option>disabled</option>
                </select>
            </div>
            <input type="hidden" name="operation" value="create">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <?php			
					}
					else if($_POST['operation'] == "editUser")
					{
						$user = getUserDetail($_POST['id']);
			?>
    <h2>Edit user Details</h2>
    <div class="forms">
        <form method="post" action="adminOps.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['emailid'] ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="phone">Phone No.</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone'] ?>"
                    required>
            </div>
            <input type="hidden" name="role" value="<?php echo $user['role'] ?>">
            <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
            <input type="hidden" name="operation" value="update">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <?php			
					}
					else if($_POST['operation'] == "deleteUser")
					{?>
    <div class="forms" style="display:none">
        <form method="post" action="adminOps.php" id="dltForm">
            <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
            <input type="hidden" name="operation" value="delete">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <script>
        document.getElementById("dltForm").submit();
        </script>
    </div>
    <?php
                        }
                    ?>
    <?php		
				}
				else
				{
			?>
    <h1>
        <center>User List</center>
    </h1>
    <!-- <form action="#" method="POST" style="margin: 20px 0px;">
        <input type="hidden" name="operation" value="createUser">
        <input type="submit" class="btn btn-success" value="Add new user">
    </form> -->
    <?php 
						if(!empty($_GET['pageNo']))
					  	{
					  		$pageNo  = $_GET["pageNo"];
					  	} 
					?>
    <h5>Page No. <?php echo $pageNo; ?></h5>
    <table class="table">
        <thead style="background-color: #0984e3;">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone No.</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
					  		$users = getUsers($limit=10, $pageNo, 'staff');
					  		while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
					  		{ if($row["role"]=="staff"){
					  	?>
            <tr>
                <th scope="row"><?php echo $row["id"]; ?></th>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["emailid"]; ?></td>
                <td><?php echo $row["phone"]; ?></td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row["id"] ?>">
                        <input type="hidden" name="operation" value="editUser">
                        <button type="submit" class="btn btn-primary" value="Edit"><i
                                class="fas fa-user-edit"></i></button>
                    </form>
                </td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row["id"] ?>">
                        <input type="hidden" name="operation" value="deleteUser">
                        <button type="submit" class="btn btn-danger" value="Delete"><i
                                class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php	
							  }}
					  	?>
        </tbody>
    </table>
    <div class="conatiner">
        <div class="row">
            <div class="col-2">
                <?php  

                                    $totalUsers = getUserCount('staff');
									$maxPages = (int)($totalUsers["COUNT(id)"]/$limit)+1;

								?>
                <form method="GET" action="#" id="pageForm">
                    <input type="hidden" name="page" value="userList">
                    <input type="hidden" name="pageNo" id="pageNo">
                </form>
                <button onclick="changePage(<?php echo $pageNo-1; ?>)" class="btn btn-primary"
                    <?php if($pageNo <= 1){ echo "disabled";} ?>>
                    < </button>
                        <button onclick="changePage(<?php echo $pageNo-1; ?>)" class="btn btn-primary"
                            <?php if($pageNo <= 1){ echo "disabled";} ?>><?php echo $pageNo-1; ?></button>
                        <button onclick="changePage(<?php echo $pageNo; ?>)" class="btn btn-primary"
                            <?php if($pageNo >= $maxPages){ echo "disabled";} ?>><?php echo $pageNo; ?></button>
                        <button onclick="changePage(<?php echo $pageNo+1; ?>)" class="btn btn-primary"
                            <?php if($pageNo >= $maxPages){ echo "disabled";} ?>><?php echo $pageNo+1; ?></button>
                        <button onclick="changePage(<?php echo $pageNo+1; ?>)" class="btn btn-primary"
                            <?php if($pageNo >= $maxPages){ echo "disabled";} ?>>></button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    function changePage(pageNo) {
        document.getElementById("pageNo").value = pageNo;
        document.getElementById("pageForm").submit();
    }

    function deleteFrm() {
        document.getElementById(document.getElementById('deleteId').value).submit();
    }

    function setDeleteId(id) {
        document.getElementById('deleteId').value = id;
    }
    </script>

    <?php 
				} 
			?>
</div>
<?php
	}
?>