<?php
include ("connection.php");

session_start();

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['login'])))
	{
	$username = mysqli_real_escape_string($connection, $_POST['username']);
	$password = md5($_POST['password']);
	$query = "SELECT id, acctype FROM users WHERE email = '$username' AND password = '$password'";
	$result = mysqli_query($connection, $query);
	$row = mysqli_fetch_array($result);
	$count = mysqli_num_rows($result);
	$privilege = $row["acctype"];
    $uid = $row["id"];
	if ($count == 1)
		{
		$_SESSION['login_user'] = $username;
		$_SESSION['privilege'] = $privilege;
        $_SESSION['uid'] = $uid;
		header("location: home.php");
		}
	  else
		{
		$alert = "Username or Password is Invalid";
		echo "<script>alert('$alert')</script>";
		}
	}

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['register'])))
	{
	$first_name = mysqli_real_escape_string($connection, $_POST['firstname']);
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$password = md5($_POST['password']);
	$account = mysqli_real_escape_string($connection, $_POST['account']);
	$query = "INSERT INTO users (name,email,password,acctype) VALUES ('$first_name','$email','$password','$account')";
	$result = mysqli_query($connection, $query);
	if ($result)
		{
		echo "<script>alert('Registered')</script>";
		}
	  else
		{
		echo "<script>alert('error')</script>";
		}
	} ?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thumbs-Up! | Help us help yourself</title>

    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        a:hover {
            color: #000000 !important;
        }
        .maincon {
            width: 100%;
            height: 15%;
            background-color: #3498db;
        }
        .panel-login {
            border-color: #333;
            -webkit-box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
            box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
        }
        .ul {
            list-style: none;
            display: flex;
            font-size: 20px;
        }
        .panel-heading a {
            color: #000000;
            text-decoration: none;
            font-weight: normal;
        }
        .panel-heading a:hover {
            background-color: #FF7800;
        }
        .active {
            background-color: #E0E0E0;
            color: #006699 !important;
            -webkit-box-shadow: inset 0px 0px 8px 0px rgba(143, 143, 143, 1);
            -moz-box-shadow: inset 0px 0px 8px 0px rgba(143, 143, 143, 1);
            box-shadow: inset 0px 0px 8px 0px rgba(143, 143, 143, 1);
        }
        .logo {
            width: 600px;
            height: 200px;
            background: url("LOGO.png");
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>

    <script>
        $(function() {

            $('#login-link').click(function(e) {
                $("#login-form").delay(100).fadeIn(100);
                $("#register-form").fadeOut(100);
                $(this).removeClass('active');
                $('#register-link').addClass('active');
                e.preventDefault();
            });

            $('#register-link').click(function(e) {
                $("#register-form").delay(100).fadeIn(100);
                $("#login-form").fadeOut(100);
                $(this).removeClass('active');
                $('#login-link').addClass('active');
                e.preventDefault();
            });

            $('#register').click(function(e) {
                $("#register-form").delay(100).fadeIn(100);
                $("#login-form").fadeOut(100);
                $('#login-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });

        });
    </script>
</head>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="logo">

                </div>
            </div>
        </div>
		
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-login">
                    <div class="panel-heading" style="padding: 0px;font-size: 20px;">
                        <div class="row" style="margin-left: 0px;margin-right: 0px">
                            <div class="col-xs-6 text-center" style="padding:0px;">
                                <li style="padding-top:15px;list-style:none;padding-bottom:15px;">
                                    <a href="#login" id="login-link" style="padding-left: 53px;padding-right: 53px;padding-top: 15px;padding-bottom: 15px;">SIGN IN</a>
                                </li>
                            </div>
                            <div class="col-xs-6 text-center" style="padding: 0px;">
                                <li style="padding-top:15px;list-style:none;padding-bottom:15px;">
                                    <a href="#register" id="register-link" class="active" style="padding-left:50px;padding-right: 40px;padding-top: 15px;padding-bottom: 15px;">REGISTER</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="" method="post" class="form-signin" id="login-form">
                                    <div class="form-group">
                                        <input type="name" class="form-control" placeholder="Email" name="username" id="username" required/>
                                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" required/>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" class="form-control btn btn-warning logbutton" value="LOG IN" name="login" id="login" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <a href="#" style="color:#000000" id="register">Don't have an account ? Sign Up Here</a>
                                    </div>
                                </form>
                                <form action="" method="post" class="form-signin" id="register-form" style="display: none;">
                                    <div class="form-group">
                                        <div class="col-sm-12" style="padding-left: 0px;padding-right: 2px;">
                                            <input type="name" class="form-control" name="firstname" id="firstname" placeholder="Name" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required/>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required/>
                                        <select name="account" id="account" class="form-control">
                                            <option value="" disabled selected style="display: none;">Account Type</option>
                                            <option value="normal">Normal</option>
                                            <option value="vendor">Vendor</option>
                                        </select>
                                    </div>
                                    <input type="submit" class="form-control btn btn-warning logbutton" value="SIGN UP" name="register" id="register" />
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>