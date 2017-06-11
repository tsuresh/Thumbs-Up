<?php 
include("template/header.php"); 
include("../connection.php"); 


$errors = "";
$uid = $_SESSION["uid"];

if(isset($_POST["submitentry"])){

    $itemname = $_POST["sname"];
    $descrip = $_POST["sdesc"];
    $storeid = $_POST["shopchoose"];
    $reqpoints = $_POST["reqpoints"];


    if(!empty($itemname) && !empty($descrip) && !empty($storeid) && !empty($reqpoints)){

        $itemname = mysqli_real_escape_string($connection,$itemname);
        $descrip = mysqli_real_escape_string($connection,$descrip);
        $storeid = mysqli_real_escape_string($connection,$storeid);
        $reqpoints = mysqli_real_escape_string($connection,$reqpoints);

        mysqli_query($connection,"INSERT INTO `offers` (`id`, `name`, `orgid`, `uid`, `description`, `pic`, `requiredpoints`) VALUES (NULL, '$itemname', '$storeid', '$uid', '$descrip', '', '$reqpoints');");

        $errors = "Successfully registered";

    } else {
        $errors = "Above fields are required!";
    }
}

?>


<h1>Offer Submission</h1>

<div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Registration form</div>

        <form action="#" method="post" style="padding:20px;">
            	<p><input type="text" name="sname" placeholder="Offer name" class="form-control"/></p>
                <p><input type="text" name="sdesc" placeholder="Offer Description" class="form-control"/></p>
                <p><input type="text" name="reqpoints" placeholder="Required Points" class="form-control"/></p>
                <p>
                <select name="shopchoose" class="form-control">
                    <?php
                    $q = mysqli_query($connection,"SELECT id, storename FROM stores WHERE uid='5';");
                    while($d=mysqli_fetch_array($q)){
                    ?>
                        <option value="<?php echo $d['id'] ?>"><?php echo $d["storename"] ?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>
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