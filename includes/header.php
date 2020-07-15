<!DOCTYPE html>
<html style="height: 100%;">

<head>
    <title>Services</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed&family=Roboto:ital,wght@0,400;1,300&family=Teko:wght@500&display=swap"
        rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/e78986ac72.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>

<body style="height: 100%;">
    <div class="container-fluid webContainer">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width:100%">
                <a class="navbar-brand" href="#"><i class="fas fa-wrench"></i> Services</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <?php if($_SESSION['login_role'] == 'customer') {?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('home')">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('feedback')">Feedback</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('service')">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('myBookings')">Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('profile')">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('logout')">Logout</a>
                        </li>
                    </ul>
                    <?php } else if($_SESSION['login_role'] == 'staff') { ?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('home')">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('myservices')">My Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('bookings')">Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('profile')">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('logout')">Logout</a>
                        </li>
                    </ul>
                    <?php } else if($_SESSION['login_role'] == 'admin') { ?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('home')">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('userList')">List of users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('providerList')">List of Providers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('services')">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('allBookings')">Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('feedbacks')">Feedbacks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('profile')">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('logout')">Logout</a>
                        </li>
                    </ul>
                    <?php } else { ?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('home')">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('service')">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('login')">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="bodyType('register')">Register</a>
                        </li>
                    </ul>
                    <?php } ?>
                    <div class="userType ml-auto">
                        <?php 
						if(!empty($_SESSION['login_role']))
						{
						?>
                        <center>
                            <?php echo $_SESSION['login_role'] == "staff" ? "Service Provider" : $_SESSION['login_role']; ?>
                        </center>
                        <?php 
						}
						?>
                    </div>
                </div>
            </nav>
        </div>

        <?php if(!empty($_SESSION['message'])) 
			{?>
        <div class="row message">
            <div class="col-12">
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        <?php
			$_SESSION['message'] = ''; 
			} ?>
        <form action="#" method="get" id="typeForm">
            <input type="hidden" name="page" id="bodyType">
        </form>
        <div class="row websiteBody">