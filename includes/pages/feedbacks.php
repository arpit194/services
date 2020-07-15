<?php 
	
	if($_SESSION['login_role'] != 'admin')
	{
		header("location:index.php");
	}
	else
	{
?>

<h1>
    <center>Feedbacks</center>
</h1>
<?php 
$limit = 5;
$pageNo = 1;
						if(!empty($_GET['pageNo']))
					  	{
					  		$pageNo  = $_GET["pageNo"];
					  	} 
					?>
<table class="table">
    <thead style="background-color: #0984e3;">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Subject</th>
            <th scope="col">Message</th>
        </tr>
    </thead>
    <tbody>
        <?php
					  		$users = getFeedback($limit, $pageNo);
					  		while($row = mysqli_fetch_array($users,MYSQLI_ASSOC))
					  		{ 
					  	?>
        <tr>
            <th><?php echo $row["user"]; ?></th>
            <td><?php echo $row["subject"]; ?></td>
            <td><?php echo $row["feedback"]; ?></td>
        </tr>
        <?php	
							  }
					  	?>
    </tbody>
</table>
<div class="conatiner">
    <div class="row">
        <div class="col-12">
            <?php  

									$totalUsers = getFeedbackCount();
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