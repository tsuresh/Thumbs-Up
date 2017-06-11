<?php

session_start();

if( !empty($_SESSION["login_user"]) ){

	if($_SESSION["privilege"] == "vendor"){
		
		
	} else {
		header("Location: ../index.php");
	}

} else {
	header("Location: ../index.php");
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Store Registration</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/material-kit.css" rel="stylesheet" />

</head>

<body>


<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
    	<div class="navbar-header">
    		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
    		</button>
    		<a class="navbar-brand" href="#">ThumbsUp</a>
    	</div>

    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    		<ul class="nav navbar-nav">
				<li><a href="storeregister.php">Store Registration</a></li>
        		<li><a href="itemsubmit.php">Item Submission</a></li>
				<li><a href="offersubmit.php">Offer Submission</a></li>
				<li><a href="../logout.php">Logout</a></li>
    		</ul>
    	</div>
	</div>
</nav>

<div class="container">
