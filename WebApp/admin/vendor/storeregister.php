<?php 
include("template/header.php"); 
include("../connection.php"); 


$errors = "";
$uid = $_SESSION["uid"];

if(isset($_POST["submitentry"])){
    $storename = $_POST["sname"];
    $location = $_POST["slocation"];

    if(!empty($storename) && !empty($location)){

        $storename = mysqli_real_escape_string($connection,$storename);
        $location = mysqli_real_escape_string($connection,$location);

        mysqli_query($connection,"INSERT INTO `stores` (`id`, `storename`, `location`, `uid`) VALUES (NULL, '$storename', '$location', '$uid');");

        $errors = "Successfully registered";

    } else {
        $errors = "Above fields are required!";
    }
}

?>


<h1>Store Registration</h1>

<div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Registration form</div>

        <form action="#" method="post" style="padding:20px;">
            	<p><input type="text" name="sname" placeholder="Store name" class="form-control"/></p>
                <p><input type="text" name="slocation" placeholder="Location" class="form-control"/></p>
                <p><input type="submit" name="submitentry" value="Submit Entry" class="btn btn-primary btn-round"/></p>
        </form>
        <?php
        if(!empty($errors)){
            echo '<div class="alert alert-warning">
    <div class="container-fluid">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true"><i class="material-icons">clear</i></span>
	  </button>'.$errors.'
    </div>
</div>';
        }
        ?>

</div>


<?php include("template/footer.php");?>