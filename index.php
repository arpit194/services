<?php
	session_start();
	// $_SESSION = null;
	if(empty($_SESSION['login_role']))
	{
		$_SESSION['login_role'] = "visitor";
	}
	include 'includes/header.php';
	include 'includes/config.php';
	include 'includes/functions.php';
	if(isset($_GET['page']))
	{
		include 'includes/pages/'.$_GET['page'].'.php';
	}
	else
	{
		include 'includes/pages/home.php';
	}
?>


<script type="text/javascript">
function bodyType(type) {
    document.getElementById('bodyType').value = type;
    document.getElementById('typeForm').submit();
}
</script>

<?php
	include 'includes/footer.php';
?>