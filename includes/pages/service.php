<?php 
    echo($_SESSION['login_role'] == 'staff' || $_SESSION['login_role'] == 'admin');
	if($_SESSION['login_role'] == 'staff' || $_SESSION['login_role'] == 'admin')
	{
		//header("location:index.php");
	}
	else
	{
?>

<div class="col-12">
    <h1>
        <center>Services</center>
    </h1>
    <div class="forms">
        <form method="post" action="adminOps.php" id="loginForm">
            <div class="form-group">
                <label for="services">Services</label>
                <select class="form-control" id="services" name="sid">
                    <option value="-1">Select a service</option>
                    <?php $services = getServices1();
                    while($row = mysqli_fetch_array($services,MYSQLI_ASSOC))
                    {
                    ?>
                    <option value="<?php echo $row['sid'] ?>"><?php echo $row['sname'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="providers">Service Providers</label>
                <select class="form-control" id="providers" name="uid">
                    <option value="-1">First select a service</option>
                </select>
            </div>
            <div class="form-group">
                <label for="locations">Locations</label>
                <select class="form-control" id="locations" name="location">
                    <option value="-1">First select a provider</option>
                </select>
            </div>
            <?php if($_SESSION['login_role']=='customer') { ?>
            <div class="form-groupcol-6 p-0">
                <label for="fromDate">Date</label>
                <input type="date" class="form-control" id="fromDate" name="date">
            </div><br>
            <h6 id="rate">Rate:</h6>
            <input type="submit" class="btn btn-primary mt-3" value="Book" />
            <input type="hidden" name="operation" value="bookService">
            <?php } else if($_SESSION['login_role']=='visitor') { ?>
            <h6>Please login to book a service</h6>
            <?php } ?>
        </form>
    </div>
</div>

<script>
var dataG;
$('#services').on('change', () => {
    $.ajax({
        type: "POST",
        url: "adminOps.php",
        data: {
            operation: 'getProviders',
            sid: document.getElementById("services").value ? document.getElementById("services").value :
                -1
        },
        success: function(data) {
            dataG = JSON.parse(data);
            if (dataG.length == 0) {
                $('#providers').html("");
                $('#providers').append("<option>No Providers for this service</option>");
            } else {
                $('#providers').html("");
                $('#providers').append("<option value='-1'>Select a Provider</option>");
                $('#locations').html("");
                $('#locations').append("<option value='-1'>Select a Location</option>");
                for (var i = 0; i < dataG.length; i++) {
                    $.ajax({
                        type: "POST",
                        url: "adminOps.php",
                        data: {
                            operation: 'getUserById',
                            uid: dataG[i].uid
                        },
                        success: function(data) {
                            data = JSON.parse(data);
                            $('#providers').append("<option value='" + data.id +
                                "'>" + data.name + "</option>");
                        },
                        error: function(textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                }
            }

        },
        error: function(textStatus, errorThrown) {
            alert(errorThrown);
        }

    });
})

$('#providers').on('change', () => {
    $.ajax({
        type: "POST",
        url: "adminOps.php",
        data: {
            operation: 'getProviderById',
            sid: document.getElementById("services").value ? document.getElementById("services").value :
                -1,
            uid: document.getElementById("providers").value ? document.getElementById("providers")
                .value :
                -1
        },
        success: function(data) {
            data = JSON.parse(data);
            console.log(data);
            $('#rate').html("Rate:" + data.rate);
            if (dataG.length == 0) {
                $('#locations').html("");
                $('#locations').append("<option>No Location for this provider</option>");
            } else {
                var a = data.locations;
                a = a.replace(/\s/g, '');
                a = a.split(',');
                if (a.length == 0) {
                    $('#locations').html("");
                    $('#locations').append("<option>No Location for this provider</option>");
                } else {
                    $('#locations').html("");
                    $('#locations').append("<option value='-1'>Select a Location</option>");
                    for (var i = 0; i < a.length; i++) {
                        $('#locations').append("<option>" + a[i] + "</option>");
                    }
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
?>