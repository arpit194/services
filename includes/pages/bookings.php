<?php 
	
	if($_SESSION['login_role'] != 'staff')
	{
		header("location:index.php");
	}
	else
	{
?>

<div class="col-12">
    <?php 
				$limit = 5;
				$pageNo = 1;
				if($_SERVER["REQUEST_METHOD"] == "POST")
				{
					if($_POST['operation'] == "acceptBooking")
					{
						$user = getBookingById($_POST['bid']);
			?>
    <h2>Edit Booking</h2>
    <div class="forms" style="display:none">
        <form method="post" action="adminOps.php" id="dltForm">
            <input type="hidden" name="bid" value="<?php echo $_POST['bid'] ?>">\
            <input type="hidden" name="status" value="<?php echo $_POST['status'] ?>">
            <input type="hidden" name="operation" value="acceptBooking">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <script>
        document.getElementById("dltForm").submit();
        </script>
    </div>
    <?php			
					}
					else if($_POST['operation'] == "cancelBooking")
					{?>
    <div class="forms" style="display:none">
        <form method="post" action="adminOps.php" id="dltForm">
            <input type="hidden" name="bid" value="<?php echo $_POST['bid'] ?>">
            <input type="hidden" name="operation" value="cancelBooking">
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
        <center>Bookings</center>
    </h1>
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
                <th scope="col">Booked By</th>
                <th scope="col">Phone No.</th>
                <th scope="col">Location</th>
                <th scope="col">Service</th>
                <th scope="col">Service Provider</th>
                <th scope="col">Date</th>
                <th scope="col">Accept</th>
                <th scope="col">Cancel</th>
            </tr>
        </thead>
        <tbody>
            <?php
					  		$users = getBookingsServiceProvider($_SESSION['login_id'], $limit=10, $pageNo);
					  		while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
					  		{   $x = getUserDetail($row['buid']);
					  	?>
            <tr
                style="<?php $a = $row["status"] == 'accepted' ? 'background-color:#00b894; color: white;' : ''; echo $a; ?>">
                <th scope="row"><?php echo $x['name']; ?></th>
                <th scope="row"><?php echo $x["phone"]; ?></th>
                <th scope="row"><?php echo $row["location"]; ?></th>
                <td><?php echo getServicesById($row["sid"])['sname']; ?></td>
                <td><?php echo getUserDetail($row["uid"])['name']; ?></td>
                <td><?php echo $row["date"]; ?></td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="bid" value="<?php echo $row["bid"] ?>">
                        <input type="hidden" name="status" value="<?php echo $row["status"] ?>">
                        <input type="hidden" name="operation" value="acceptBooking">
                        <?php if($row["status"] == 'pending') {?>
                        <button type="submit" class="btn btn-success" value="Accept"><i
                                class="fas fa-thumbs-up"></i></button>
                        <?php } else { ?>
                        <span>Accepted</span>
                        <?php } ?>
                    </form>
                </td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="bid" value="<?php echo $row["bid"] ?>">
                        <input type="hidden" name="operation" value="cancelBooking">
                        <button type="submit" class="btn btn-danger" value="Cancel"><i
                                class="fas fa-window-close"></i></button>
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

									$totalUsers = getBookingCount();
									$maxPages = (int)($totalUsers["COUNT(bid)"]/$limit)+1;

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