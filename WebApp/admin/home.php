<?php
include("connection.php");
session_start();
if((isset($_SESSION['login_user']))){
	$user = $_SESSION['login_user'];
	//header("location: index.php");	
}else{
	header("location: index.php");
}

if($_SESSION["privilege"] == "vendor"){
		header("Location: vendor");
		exit();
}


$result = mysqli_query($connection,"SELECT name FROM users WHERE email = '$user'");
$row    = mysqli_fetch_array($result);
$name   = $row['name'];
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Thumbs-Up | Home</title>
	
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		.mm{
			-webkit-box-shadow: 3px 3px 12px 0px rgba(0,0,0,0.75);
			-moz-box-shadow: 3px 3px 12px 0px rgba(0,0,0,0.75);
			box-shadow: 3px 3px 12px 0px rgba(0,0,0,0.75);
		}
		.active{
			background-color: dodgerblue;
		}
		.header{
			width:100%;
			height:50px;
			
		}
		.footer{	
			position: absolute;
			right: 0;
			bottom: 0;
			left: 0;
			padding: 1rem;
			background-color:#ffb777;
			text-align: center;
			height:50px;
		}
	</style>
	
	<script>
	
	$(function(){
		$('#new-request-link').click(function() {
			$("#requests").delay(100).fadeIn(100);
			$("#account").fadeOut(100);
		});	
		$('#account-link').click(function() {
			$("#account").delay(100).fadeIn(100);
			$("#requests").fadeOut(100);
		});	
	});
	
	</script>
</head>
<body>
	
	<div class="header">
		<div style="width:85%;float:left;">
		
		</div>
		<div style="float:right;background-color:#ffffff;">
			<p><img src="css/img/ico.png" style="width:50px;height:50px;opacity0.7;"></p>
		</div>
		<div style="float:right;background-color:#ffffff;height:60px;padding:5px;opacity:0.7;">
			<p style="font-size: 16px;margin-bottom:-5px;"><?php echo $name?></p>
			<span style="font-size:12px;">25 PTS</span><br>
			<a href="logout.php">LOGOUT</a>
		</div>
		
	</div>
	
	<div class="container" style="margin-top:5%;">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-6 col-md-offset-3">
					<img src="search img.png" style="margin-left:-45px;">
				</div>
				<div class="col-md-9 col-md-offset-2" style="margin-top:20px;">
					<form class="form-inline" action="search.php" method="get">
						<div class="form-group">
							<select class="form-control">
								<option value="restaurants">Restuarants</option>
								<option value="cafes">Cafe`s</option>
								<option value="eating-house">Eating House</option>
								<option value="supermarkets">Supermarkets</option>
								<option value="groceries">Groceries</option>
								<option value="cinemas">Cinemas</option>
								<option value="other">Other</option>
							</select>
							<input type="text" class="form-control" name="q" placeholder="Search" style="width:500px;">
							<input type="submit" class="form-control btn btn-warning" value="SEARCH" name="submit" id="search"/>
						</div>
					</form>
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="footer">
		<a href="#">ABOUT US</a> | <a href="#">BECOME A CONTRIBUTER</a>
	</div>
	
</body>
</html>