<?php 
	
	if($_SESSION['login_role'] != 'staff')
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
					if($_POST['operation'] == "createService")
					{
			?>
    <h2>Create New Service</h2>
    <div class="forms">
        <form method="post" action="adminOps.php">
            <div class="form-group">
                <label for="sid">Service</label>
                <select class="form-control" id="sid" name="sid">
                    <?php $services = getServices1();
                while($row = mysqli_fetch_array($services,MYSQLI_ASSOC))
                {
                ?>
                    <option value="<?php echo $row['sid'] ?>"><?php echo $row['sname'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="locations">Locations</label>
                <input type="text" class="form-control" id="locations" name="locations"
                    placeholder="Enter ','(comma) seperated location names." required>
            </div>

            <div class="form-group">
                <label for="rate">Rate</label>
                <input type="text" class="form-control" id="rate" placeholder="Enter rate per hour" name="rate"
                    required>
            </div>
            <input type="hidden" name="operation" value="createServiceProvider">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <?php			
					}
					else if($_POST['operation'] == "editService")
					{
						$service = getServicesProvidedBySid($_SESSION['login_id'], $_POST['id']);
			?>
    <h2>Edit Service</h2>
    <div class="forms">
        <form method="post" action="adminOps.php">
            <div class="form-group">
                <label for="locations">Locations</label>
                <input type="text" class="form-control" id="locations" name="locations"
                    value="<?php echo $service['locations'] ?>" required>
            </div>
            <div class="form-group">
                <label for="rate">Rate</label>
                <input type="text" class="form-control" id="rate" name="rate" value="<?php echo $service['rate'] ?>"
                    required>
            </div>
            <input type="hidden" name="sid" value="<?php echo $_POST['id'] ?>">
            <input type="hidden" name="operation" value="updateServicesProvider">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <?php			
					}
					else if($_POST['operation'] == "deleteService")
					{
    ?>
    <div class="forms" style="display:none">
        <form method="post" action="adminOps.php" id="dltForm">
            <input type="hidden" name="sid" value="<?php echo $_POST['id'] ?>">
            <input type="hidden" name="operation" value="deleteServiceProvider">
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
        <center>Services</center>
    </h1>
    <form action="#" method="POST" style="margin: 20px 0px;">
        <input type="hidden" name="operation" value="createService">
        <input type="submit" class="btn btn-success" value="Add new Service">
    </form>
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
                <th scope="col">Service</th>
                <th scope="col">Locations</th>
                <th scope="col">Rate</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
                              $users = getServicesProvided($_SESSION['login_id'], $limit, $pageNo);
					  		while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
					  		{
					  	?>
            <tr>
                <th scope="row"><?php echo getServicesById($row["sid"])['sname']; ?></th>
                <td><?php echo $row["locations"]; ?></td>
                <td><?php echo $row["rate"]; ?></td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row["sid"] ?>">
                        <input type="hidden" name="operation" value="editService">
                        <button type="submit" class="btn btn-primary" value="Edit"><i
                                class="fas fa-user-edit"></i></button>
                    </form>
                </td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row["sid"] ?>">
                        <input type="hidden" name="operation" value="deleteService">
                        <button type="submit" class="btn btn-danger" value="Delete"><i
                                class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php	
							  }
					  	?>
        </tbody>
    </table>
    <div class="conatiner">
        <div class="row">
            <div class="col-2">
                <?php 
                                    $totalUsers = getServiceProviderCount($_SESSION['login_id']);
									$maxPages = (int)($totalUsers["COUNT(uid)"]/$limit)+1;

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