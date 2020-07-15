<?php
	session_start();
	include 'includes/config.php';
	include 'includes/functions.php';
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if($_POST['operation'] == "update")
		{
			updateUserDetail($_POST['id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['role']);
		}
		else if($_POST['operation'] == "delete")
		{
			deleteUser($_POST['id']);
		}
		else if($_POST['operation'] == "create")
		{
			register($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'], $_POST['status'], "admin");
		}
		else if($_POST['operation'] == "getUserById")
		{
			echo json_encode(getUserDetail($_POST['uid']));
		}
		else if($_POST['operation'] == "createService")
		{
			addService($_POST['sname']);
		}
		else if($_POST['operation'] == "deleteService")
		{
			deleteService($_POST['sid']);
		}
		else if($_POST['operation'] == "updateService")
		{
			updateService($_POST['sid'], $_POST['sname']);
		}
		else if($_POST['operation'] == "updateServicesProvider")
		{
			updateServiceProvider($_SESSION['login_id'], $_POST['sid'], $_POST['locations'], $_POST['rate']);
		}
		else if($_POST['operation'] == "createServiceProvider")
		{
			addServiceProvider($_SESSION['login_id'], $_POST['sid'], $_POST['locations'], $_POST['rate']);
		}
		else if($_POST['operation'] == "deleteServiceProvider")
		{
			deleteServiceProvider($_SESSION['login_id'], $_POST['sid']);
		}
		else if($_POST['operation'] == "getProviderById")
		{
			echo json_encode(getServicesProvidedBySid($_POST['uid'], $_POST['sid']));
		}
		else if($_POST['operation'] == "bookService")
		{
			bookService($_SESSION['login_id'], $_POST['sid'], $_POST['uid'], $_POST['location'], $_POST['date']);
		}
		else if($_POST['operation'] == "editBooking")
		{
			updateBookingDetails($_POST['bid'], $_POST['sid'], $_POST['location'], $_POST['date']);
		}
		else if($_POST['operation'] == "cancelBooking")
		{
			deleteBooking($_POST['bid']);
		}
		else if($_POST['operation'] == "acceptBooking")
		{
			updateBookingStatus($_POST['bid'], $_POST['status']=='pending' ? 'accepted' : 'pending');
		}
		else if($_POST['operation'] == "updateProfile")
		{
			updateUserDetail($_SESSION['login_id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['role']);
		}
		else if($_POST['operation'] == "changePassword")
		{
			changePassword($_POST['oldPswd'], $_POST['newPswd'], $_POST['cnfPswd']);
		}
		else if($_POST['operation'] == "rejectRequest")
		{
			processRequest('reject', $_POST['id'], $_POST['carId']);
		}
		else if($_POST['operation'] == "forgotPassword")
		{
			resetPassword($_POST['email']);
		}
		else if($_POST['operation'] == "feedback")
		{
			addFeedback($_SESSION['login_name'], $_POST['subject'], $_POST['message']);
		}
		else if($_POST['operation'] == "getProviders")
		{
			$providers = getServicesProvider($_POST['sid'], 1000, 1);
			$providers = mysqli_fetch_all($providers,MYSQLI_ASSOC);
			echo json_encode($providers);
		}
	}

?>