<?php
	//Check Email is already registered or not
	function checkEmail($email)
	{	
    global $conn;
		$email = mysqli_real_escape_string($conn,$email);
		$sql = "SELECT id FROM users WHERE emailid = '$email'";
		$result = mysqli_query($conn,$sql);
		
		$count = mysqli_num_rows($result);
		return $count;	
	}

	//Check whether the service has been already registered by the service provider or not
	function checkServiceRegistered($uid, $sid)
	{
		global $conn;
		$uid = mysqli_real_escape_string($conn,$uid);
		$sid = mysqli_real_escape_string($conn,$sid);
		$sql = "SELECT uid FROM serviceprovider WHERE uid = $uid and sid = $sid";
		$result = mysqli_query($conn,$sql);
		
		$count = mysqli_num_rows($result);
		return $count;		
	}

	//Check whether a service has been already added or not
	function checkService($sname)
	{
		global $conn;
		$sname = mysqli_real_escape_string($conn,$sname);
		$sql = "SELECT sid FROM services WHERE sname = '$sname'";
		$result = mysqli_query($conn,$sql);
		
		$count = mysqli_num_rows($result);
		return $count;		
	}

	//Signup Code
	function register($name, $email, $password, $phone, $role='customer')
	{ 
    global $conn;
	  $name = mysqli_real_escape_string($conn, $name);
	  $email = mysqli_real_escape_string($conn, $email);
      $check = checkEmail($email);
      if($check == 1)
      {
      	$error = "Email already exists";
      	$_SESSION['message'] = $error;
        
        //change redirect location
        header("Location: index.php?page=register");
         
      }
      else
      {
      	$password = md5(mysqli_real_escape_string($conn,$password));
      	$sql = "Insert into users(name, emailid, password, role, phone) value ('$name', '$email', '$password', '$role', '$phone')";
      	$result = mysqli_query($conn,$sql);
      	if ($result == 1)
      	{

             $_SESSION['message'] = "Registered Successfully";
             //Change redirect location
             header("Location: index.php?page=login");
      	}
      	else
      	{
      		$error = "Some Error occured";
      		$_SESSION['message'] = $error;
            //Change redirect location
            header("Location: index.php?page=register");
             
      	}	
  	  }
	}


	//Login Function
	function login($email, $password)
	{ 
    global $conn;
	  $email = mysqli_real_escape_string($conn,$email);
      $password = md5(mysqli_real_escape_string($conn,$password)); 
      
      $sql = "SELECT id, name, emailid, role, phone FROM users WHERE emailid = '$email' and password = '$password'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      #$active = $row['active'];
      $count = mysqli_num_rows($result);
      if($count == 1)
       	{ 
         
         	
         	$_SESSION["login_id"] = $row["id"];
         	$_SESSION["login_name"] = $row["name"];
         	$_SESSION["login_email"] = $row["emailid"];
         	$_SESSION["login_role"] = $row["role"];
          $_SESSION["login_phone"] = $row["phone"];
         	$_SESSION["message"] = "Successfully logged in";
          header("Location: index.php");
 		     //Redirect to the page according to the $_SESSION["login_role"]
      	}
      	else 
      	{
          $error = "Either Login Id or Password is invalid";
          $_SESSION["message"] = $error;
          header("Location: index.php?page=login");
      	}
	}

	//Logout Function
	function logout($message="Successfully logged out")
	{
	  $_SESSION["login_id"] = '';
      $_SESSION["login_name"] = '';
      $_SESSION["login_email"] = '';
      $_SESSION["login_role"] = '';
      $_SESSION["login_phone"] = '';
      $_SESSION["message"] = $message;
      header("Location: index.php?page=login");
	}

	//
	function changePassword($oldPassword, $newPassword, $cnfPassword)
    {
	    global $conn;
	    $id = $_SESSION['login_id'];
	    $oldPassword = md5(mysqli_real_escape_string($conn,$oldPassword));
	    $checkSql = "select password from users where id = $id";
	    $checkResult = mysqli_query($conn,$checkSql);
	    $row = mysqli_fetch_array($checkResult,MYSQLI_ASSOC);
	    if($newPassword != $cnfPassword)
	    {
	      $_SESSION['message'] = "Passwords do not match";
	      //Change redirect location
	      header("location: index.php?page=profile");
	    }
	    else if ($oldPassword == $row["password"])
	    {
	      $newPassword = md5(mysqli_real_escape_string($conn,$newPassword));
	      $updateSql = "update users set password = '$newPassword' where id = $id";
	      $updateResult = mysqli_query($conn,$updateSql);
	      if($updateResult)
	      {
	        $message = "Password Changed. Please login again";
	        logout($message);
	      }
	      else
	      {
	        $_SESSION["message"] = "Some Error Ocured";
	        //Change redirect location
	        header("location: index.php?page=profile");
	      }
	    }
	    else
	    {
	      $_SESSION["message"] = "Current Password is Incorrect.";
	      //Change redirect location
	      header("location: index.php?page=profile");
	    }
    }

	//Get Users; Role should be customer or serviceprovider
	function getUsers($limit=10, $pageNo, $role)
	{
	    global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT id, name, emailid, role, phone FROM users where role = '$role' LIMIT $start_from, $limit";
	    $result = mysqli_query($conn,$sql);
	    #$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    #$active = $row['active'];
	    #$count = mysqli_num_rows($result);
	    return $result;
	}

	//Get Total Users; Role should be customer or serviceprovider
	function getUserCount($role)
	{
	    global $conn;
      $sql = "SELECT COUNT(id) FROM users where role = '$role';";
	    $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
	}

	//Get Details of a user
	function getUserDetail($id)
	{
	    global $conn;
	    $sql = "SELECT id, name, emailid, role, phone FROM users where id='$id'";
	    $result = mysqli_query($conn,$sql);
	    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
	}

	//Update current user details
	function updateUserDetail($id, $name, $email, $phone, $role)
	{
	    global $conn;
	    $name = mysqli_real_escape_string($conn, $name);
	    $email = mysqli_real_escape_string($conn, $email);
	    $emailsql = "SELECT id FROM users WHERE emailid = '$email'";
	    $emailresult = mysqli_query($conn,$emailsql);
	    $emailrow = mysqli_fetch_array($emailresult,MYSQLI_ASSOC);

	    if(!empty($emailrow))
	    {
	      if($emailrow["id"] != $id)
	      {
	          $error = "Email already exists";
	          $_SESSION['message'] = $error;
	          //Change redirect location
	          header("Location:index.php?page=profile");
	      }
	      else
	      {
          $sql = "update users set name = '$name', emailid = '$email', phone = '$phone' , role = '$role' where id = $id";
	        $result = mysqli_query($conn,$sql);
	        if ($result == 1)
	        {
	          $_SESSION['message'] = "Updated Successfully";
	          //Change redirect location
	          header("Location:index.php?page=profile");
	        }
	        else
	        {
	          $error = "Some Error occured";
	          $_SESSION['message'] = $error;
	          //Change redirect location
	          header("Location:index.php?page=profile");
	        }
	      }
	    }
	    else
	    {
	        $sql = "update users set name = '$name', emailid = '$email', role= '$role' , phone = $phone where id = $id";
	        $result = mysqli_query($conn,$sql);
	        if ($result == 1)
	        {
	          $_SESSION['message'] = "Updated Successfully";
	          //Change redirect location
	          header("Location:index.php?page=profile");
	        }
	        else
	        {
	          $error = "Some Error occured";
	          $_SESSION['message'] = $error;
	          //Change redirect location
	          header("Location:index.php?page=profile");
	        } 
	      } 
  }
  
  function deleteUser($id)
	{
	    global $conn;
      $sql = "delete from users where id = $id";
	    $result = mysqli_query($conn,$sql);
	    if ($result == 1)
	    {
	      $_SESSION['message'] = "Deleted Successfully";
	      header("Location: index.php?page=userList");
	    }
	    else
	    {
	      $error = "Some Error occured";
	      $_SESSION['message'] = $error;
	      //header("Location: index.php?page=userList");
	    }
	}

	//Add Service Provider
	function addServiceProvider($uid, $sid, $locations, $rate)
	{
	    global $conn;
	    $uid = mysqli_real_escape_string($conn, $uid);
	    $sid = mysqli_real_escape_string($conn, $sid);
	    $locations = mysqli_real_escape_string($conn, $locations);
	    $rate = mysqli_real_escape_string($conn, $rate);
      $count = checkServiceRegistered($uid, $sid);
	    if($count == 0)
	    {
		    $sql = "insert into serviceprovider (uid, sid, locations, rate) values ($uid, $sid, '$locations', '$rate')";
		    $result = mysqli_query($conn,$sql);
		    if ($result)
		    {
          $_SESSION['message'] = "Service Providers Service Added";
		      header("Location: index.php?page=myservices");
		    }
		    else
		    {
          $_SESSION['message'] = "Some Error Occurred.";
		      header("Location: index.php?page=myservices");
		    }

		}
		else
		{
			$error = "Service already registered";
          $_SESSION['message'] = $error;
        	header("Location: index.php?page=myservices");	
		}
	}

	//Update Service details provided by Service Provider
	function updateServiceProvider($uid, $sid, $locations, $rate)
  	{
	    global $conn;
	    $uid = mysqli_real_escape_string($conn, $uid);
	    $sid = mysqli_real_escape_string($conn, $sid);
	    $locations = mysqli_real_escape_string($conn, $locations);
	    $sql = "update serviceprovider set locations = '$locations', rate = '$rate' where uid = $uid and sid = $sid";
	    $result = mysqli_query($conn,$sql);
	    if ($result == 1)
	    {
	        $_SESSION['message'] = "Updated Successfully";
	        //change redirect location
	        header("Location:index.php?page=myservices");
	          
	    }
	    else
	    {
	        $error = "Some Error occured";
	        $_SESSION['message'] = $error;
	        //change redirect location
	        header("Location:index.php?page=myservices");
	    }     
  	}

  	//To add new service
  	function addService($sname)
	{
	    global $conn;
	    $sname = mysqli_real_escape_string($conn, $sname);
	    $count = checkService($sname);
	    if($count == 0)
	    {
		    $sql = "insert into services (sname) values ('$sname')";
		    $result = mysqli_query($conn,$sql);
		    if ($result)
		    {
		      $_SESSION['message'] = "New Service Added";
		      //change redirect location
		      header("Location: index.php?page=services");
		    }
		    else
		    {
		      $_SESSION['message'] = "Some Error Occurred.";
		      //change redirect location
		      header("Location: index.php?page=services");

		    }
		}
		else
		{
			$error = "Service already added";
      		$_SESSION['message'] = $error;
        	//change redirect location
        	header("Location: index.php?page=register");			
		}
	}

	//Updating Service
	function updateService($sid, $sname)
	{
	    global $conn;
	    $sid = mysqli_real_escape_string($conn, $sid);
	    $sname = mysqli_real_escape_string($conn, $sname);
	    $count = checkService($sname);
	    if($count==0)
	    {
	          $sql = "update services set sname = '$sname' where sid = $sid";
	          $result = mysqli_query($conn,$sql);
	          if ($result == 1)
	          {
	            $_SESSION['message'] = "Service Updated Successfully";
	            //change redirect location
	            header("Location:index.php?page=services");
	          }
	          else
	          {
	            $error = "Some Error occured";
	            $_SESSION['message'] = $error;
	            //change redirect location
	            header("Location:index.php?page=services");
	          }     
	    }
	    else
	    {
	    	$error = "Service already added";
      		$_SESSION['message'] = $error;
        	//change redirect location
        	header("Location: index.php?page=services");
	    }
	}

	//Delete Service Provider for a service
	function deleteServiceProvider($uid, $sid)
	{
	    global $conn;
      $sql = "delete from serviceprovider where uid = $uid and sid = $sid";
	    $result = mysqli_query($conn,$sql);
	    if ($result == 1)
	    {
	      $_SESSION['message'] = "Deleted Successfully";
	      header("Location: index.php?page=myservices");
	    }
	    else
	    {
	      $error = "Some Error occured";
	      $_SESSION['message'] = $error;
	      header("Location: index.php?page=myservices");
	    }
	}

	//Delete a Service type
	function deleteService($sid)
	{
	    global $conn;
      $sql = "delete from services where sid = $sid";
	    $result = mysqli_query($conn,$sql);
	    if ($result == 1)
	    {
	      $_SESSION['message'] = "Deleted Successfully";
	      //change redirect location
	      header("Location: index.php?page=services");
	    }
	    else
	    {
	      $error = "Some Error occured";
	      $_SESSION['message'] = $error;
	      //change redirect location
	      header("Location: index.php?page=services");
	    }
	}

	//Get Total Service Count
	function getServiceCount()
	{
	    global $conn;
	    $sql = "SELECT COUNT(sid) FROM services;";
	    $result = mysqli_query($conn,$sql);
	    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
	}

	//Get the number of service provider for a service
	function getServiceProviderCount($sid)
	{
	    global $conn;
	    $sql = "SELECT COUNT(uid) FROM serviceprovider where sid = $sid";
	    $result = mysqli_query($conn,$sql);
	    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
	}

	//Get list of all services
	function getServices($limit=10, $pageNo)
	{
	    global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT * FROM services LIMIT $start_from, $limit";
	    $result = mysqli_query($conn, $sql);
	    return $result;
  }

  //Get list of all services without limit
	function getServices1()
	{
	    global $conn;
	    $sql = "SELECT * FROM services";
	    $result = mysqli_query($conn, $sql);
	    return $result;
  }
  
  //Get service name by id
	function getServicesById($sid)
	{
	    global $conn;
	    $sql = "SELECT * FROM services where sid = $sid";
      $result = mysqli_query($conn, $sql);
      $result = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $result;
	}

	//Get list of all service provider for a service
	function getServicesProvider($sid, $limit=10, $pageNo)
	{
	    global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT * FROM serviceprovider where sid = $sid LIMIT $start_from, $limit";
	    $result = mysqli_query($conn, $sql);
	    return $result;
	}

	//Get list all the services provided by a service provider 
	function getServicesProvided($uid, $limit=10, $pageNo)
  	{
	    global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT * FROM serviceprovider where uid = $uid  LIMIT $start_from, $limit";
	    $result = mysqli_query($conn, $sql);
	    return $result;
    }

	function getServicesProvidedByUid($uid)
  {
    global $conn;
    $sql = "SELECT * FROM serviceprovider where uid = $uid";
    $result = mysqli_query($conn, $sql);
    return $result;
  }
    
    //Get service provided by a service provider 
	function getServicesProvidedBySid($uid, $sid)
  {
    global $conn;
    $sql = "SELECT * FROM serviceprovider where uid = $uid and sid = $sid";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result,MYSQLI_ASSOC);
    return $result;
  }

	//Get the number of service provided by a service provider
	function getServiceProvidedCount($uid)
	{
	    global $conn;
	    $sql = "SELECT COUNT(sid) FROM serviceprovider where uid = $uid";
	    $result = mysqli_query($conn,$sql);
	    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
	}

	//Get list of all service provider for a service by a location
	function getServicesProviderByLocation($sid, $location, $limit=10, $pageNo)
	{
	    global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT * FROM serviceprovider where sid = $sid and locations like '%$location%' LIMIT $start_from, $limit";
	    $result = mysqli_query($conn, $sql);
	    return $result;
	}

	//Booking a Service
	function bookService($buid, $sid, $uid, $location, $date)
	{
		global $conn;
		$location = mysqli_real_escape_string($conn, $location);
	    if($date < date('Y-m-d'))
	    {
	      $_SESSION["message"] = "Date should not be in past.";
	      //change redirect location
	      header("Location: index.php?page=service");
	    }
	    else
	    {
        $sql = "insert into bookings (location, buid, sid, uid, date) values ('$location',$buid, $sid, $uid, '$date')";
        echo $sql;
	    	$result = mysqli_query($conn,$sql);
	    
		    if ($result)
		    {
		      $_SESSION['message'] = "Booking Request Sent";
		      //change redirect location
		      header("Location: index.php?page=mybookings");
		    }
		    else
		    {
		      $_SESSION['message'] = "Some Error Occurred.";
		      //change redirect location
		      //header("Location: index.php?page=service");
		    }
	    }
	}

	//Update Booking details
	function updateBookingDetails($bid, $sid, $location, $date)
	{
		global $conn;
		$location = mysqli_real_escape_string($conn, $location);
	    if($date < date('Y-m-d'))
	    {
	      $_SESSION["message"] = "Date should not be in past.";
	      //change redirect location
	      header("Location: index.php?page=home");
	    }
	    else
	    {
	    	$sql = "update bookings set location = '$location', sid = $sid, date = '$date', status='pending' where bid = $bid";
	    	$result = mysqli_query($conn,$sql);
	    
		    if ($result)
		    {
		      $_SESSION['message'] = "Booking Request Updated";
		      //change redirect location
		      header("Location: index.php?page=home");
		    }
		    else
		    {
		      $_SESSION['message'] = "Some Error Occurred.";
		      //change redirect location
		      header("Location: index.php?page=home");
		    }
	    }
	}

	//Get a list of bookings for a service provider
	function getBookingsServiceProvider($uid, $limit=10, $pageNo)
	{
		global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT * FROM bookings where uid = $uid  LIMIT $start_from, $limit";
	    $result = mysqli_query($conn, $sql);
	    return $result;	
	}

	//Get a list of bookings for a user
	function getBookingsUser($buid, $limit=10, $pageNo)
	{
		global $conn;
	    $start_from = ($pageNo-1) * $limit;
	    $sql = "SELECT * FROM bookings where buid = $buid  LIMIT $start_from, $limit";
	    $result = mysqli_query($conn, $sql);
	    return $result;	
  }
 
  function getBookingDetails($bid)
  {
    $row = getBookingById($bid);
    $sid = $row["sid"];
    $uid = $row["uid"];
    $service = getServicesProvidedBySid($uid, $sid);
    $a = mysqli_fetch_all(getServicesProvided($uid, 1000, 1),MYSQLI_ASSOC);
    $location = $service["locations"];
    $date = $row["date"];
    return array($a,$location, $date);
  }

  //Get booking by Id
  function getBookingById($bid)
  	{
	    global $conn;
	    $sql = "SELECT * FROM bookings where bid = $bid;";
	    $result = mysqli_query($conn,$sql);
	    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
  	}

	//Get a list of all bookings
	function getAllBookings($limit=10, $pageNo)
	{
		global $conn;
	    $start_from = ($pageNo-1) * $limit;
      $sql = "SELECT * FROM bookings LIMIT $start_from, $limit";
      $result = mysqli_query($conn, $sql);
	    return $result;	
	}

	//update booking status
	function updateBookingStatus($bid, $status)
	{
		global $conn;
    $sql = "update bookings set status = '$status' where bid = $bid";
	    $result = mysqli_query($conn,$sql);
	    if ($result)
		{
		    $_SESSION['message'] = "Booking Status Updated";
		    //change redirect location
		    header("Location: index.php?page=bookings");
		}
		else
		{
		    $_SESSION['message'] = "Some Error Occurred.";
		    //change redirect location
		    header("Location: index.php?page=bookings");
		}
	}

	//Get Booking Count
	function getBookingCount()
  	{
	    global $conn;
	    $sql = "SELECT COUNT(bid) FROM bookings;";
	    $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    return $row;
  	}
	//Delete a booking
	function deleteBooking($bid)
	{
    global $conn;
    $sql = "delete from bookings where bid = $bid";
	    $result = mysqli_query($conn,$sql);
	    if ($result == 1)
	    {
	      $_SESSION['message'] = "Booking Cancelled";
	      //change redirect location
	      header("Location: index.php?page=mybookings");
	    }
	    else
	    {
	      $error = "Some Error occured";
	      $_SESSION['message'] = $error;
	      //change redirect location
	      header("Location: index.php?page=mybookings");
	    }

	}

  function addFeedback($user, $subject, $message)
	{
	    global $conn;
      $subject = mysqli_real_escape_string($conn, $subject);
      $message = mysqli_real_escape_string($conn, $message);
		    $sql = "insert into feedback (user,subject,feedback) values ('$user','$subject','$message')";
		    $result = mysqli_query($conn,$sql);
		    if ($result)
		    {
		      $_SESSION['message'] = "Feedback Recieved";
		      //change redirect location
		      header("Location: index.php?page=feedback");
		    }
		    else
		    {
		      $_SESSION['message'] = "Some Error Occurred.";
		      //change redirect location
		      header("Location: index.php?page=feedback");

		    }
  }
  
  function getFeedback($limit=10, $pageNo)
	{
      global $conn;
      $start_from = ($pageNo-1) * $limit;
		    $sql = "SELECT * FROM feedback LIMIT $start_from, $limit";
		    $result = mysqli_query($conn,$sql);
		    return $result;
  }

  function getFeedbackCount()
	{
    global $conn;
    $sql = "SELECT COUNT(id) FROM feedback;";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    return $row;
  }
  
?>