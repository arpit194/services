<?php 
	
	if($_SESSION['login_role'] == 'staff' || $_SESSION['login_role'] == 'visitor')
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
					if($_POST['operation'] == "editBooking")
					{
						$user = getBookingById($_POST['bid']);
			?>
    <h2>Edit Booking</h2>
    <div class="forms">
        <form method="post" action="adminOps.php">
            <div class="form-group">
                <label for="services">Services</label>
                <select class="form-control" id="services" name="sid">
                    <option value="-1">Select a service</option>
                    <?php $services = getServicesProvidedByUid($user['uid']);
                    while($row = mysqli_fetch_array($services,MYSQLI_ASSOC))
                    {   
                        if($row['sid'] == $user['sid']){
                    ?>
                    <option selected value="<?php echo $row['sid'] ?>">
                        <?php echo getServicesById($row['sid'])['sname'] ?>
                    </option>
                    <?php } else { ?>
                    <option value="<?php echo $row['sid'] ?>"><?php echo getServicesById($row['sid'])['sname'] ?>
                    </option>
                    <?php }
                    }?>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <select class="form-control" id="location" name="location">
                    <option value="-1">Select a location</option>
                    <?php $location = getServicesProvidedBySid($user['uid'], $user['sid']);
                    $location1 = str_replace(' ', '', $location['locations']);
                    $location1 = explode(",",$location1);
                    foreach ($location1 as $l)
                    {   echo($l." ".$user['location']);
                        if($l == $user['location']){
                    ?>
                    <option selected>
                        <?php echo $l ?>
                    </option>
                    <?php } else { ?>
                    <option>
                        <?php echo $l ?>
                    </option>
                    <?php }
                    }?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo $user['date'] ?>"
                    required>
            </div><br>
            <h6 id="rate">Rate:<?php echo $location['rate'] ?></h6>
            <input type="hidden" name="bid" value="<?php echo $_POST['bid']?>">
            <input type="hidden" name="operation" value="editBooking">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <script>
    $('#services').on('change', () => {
        var bool = "<?php echo $user['uid'] ?>";
        $.ajax({
            type: "POST",
            url: "adminOps.php",
            data: {
                operation: 'getProviderById',
                sid: document.getElementById("services").value ? document.getElementById("services")
                    .value :
                    -1,
                uid: bool
            },
            success: function(data) {
                data = JSON.parse(data);
                $('#rate').html("Rate:" + data.rate);
                var a = data.locations;
                a = a.replace(/\s/g, '');
                a = a.split(',');
                if (a.length == 0) {
                    $('#location').html("");
                    $('#location').append("<option>No Location for this provider</option>");
                } else {
                    $('#location').html("");
                    $('#location').append("<option value='-1'>Select a Location</option>");
                    for (var i = 0; i < a.length; i++) {
                        $('#location').append("<option>" + a[i] + "</option>");
                    }
                }

            },
            error: function(textStatus, errorThrown) {
                alert(errorThrown);
            }

        });
    })
    </script>
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
                <th scope="col">Location</th>
                <th scope="col">Service</th>
                <th scope="col">Service Provider</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Edit</th>
                <th scope="col">Cancel</th>
            </tr>
        </thead>
        <tbody>
            <?php
					  		$users = getBookingsUser($_SESSION['login_id'], $limit=10, $pageNo);
					  		while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
					  		{
					  	?>
            <tr
                style="<?php $a = $row["status"] == 'accepted' ? 'background-color:#00b894; color: white;' : ''; echo $a; ?>">
                <th scope="row"><?php echo $row["location"]; ?></th>
                <td><?php echo getServicesById($row["sid"])['sname']; ?></td>
                <td><?php echo getUserDetail($row["uid"])['name']; ?></td>
                <td><?php echo $row["date"]; ?></td>
                <td><?php echo $row["status"]; ?></td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="bid" value="<?php echo $row["bid"] ?>">
                        <input type="hidden" name="operation" value="editBooking">
                        <button type="submit" class="btn btn-primary" value="Edit"><i class="fas fa-edit"></i></button>
                    </form>
                </td>
                <td>
                    <form action="#" method="POST">
                        <input type="hidden" name="bid" value="<?php echo $row["bid"] ?>">
                        <input type="hidden" name="operation" value="cancelBooking">
                        <button type="submit" class="btn btn-danger" value="Delete"><i
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