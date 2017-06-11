<?php 
include("template/header.php"); 
include("../connection.php"); 


$errors = "";
$uid = $_SESSION["uid"];
$output = "";

if(isset($_POST["submitentry"])){

    $itemname = $_POST["sname"];
    $descrip = $_POST["sdesc"];
    $price = $_POST["sprice"];
    $storeid = $_POST["shopchoose"];


    if(!empty($itemname) && !empty($descrip) && !empty($price) && !empty($storeid)){

        $itemname = mysqli_real_escape_string($connection,$itemname);
        $descrip = mysqli_real_escape_string($connection,$descrip);
        $price = mysqli_real_escape_string($connection,$price);
        $storeid = mysqli_real_escape_string($connection,$storeid);

        $rand = rand(464656,64657687);

        mysqli_query($connection,"INSERT INTO `items` (`id`, `name`, `orgid`, `uid`, `description`, `pic`, `price`, `rand`) VALUES (NULL, '$itemname', '$storeid', '$uid', '$descrip', '', '$price', '$rand');");

        $errors = "Successfully registered";
        $output = "<h3><a href='javascript:printImg();'>Print this QR Code</a> and place the code in your showcase.</h3><br/><img id='qrIMG' src='https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$rand."&choe=UTF-8'/>";

    } else {
        $errors = "Above fields are required!";
    }
}

?>

<script>
    function printImg() {
        pwin = window.open(document.getElementById("qrIMG").src,"_blank");
        pwin.onload = function () {window.print();
    }
</script>

<h1>Item Registration</h1>

<div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Registration form</div>

        <form action="#" method="post" style="padding:20px;">
            	<p><input type="text" name="sname" placeholder="Iteme name" class="form-control"/></p>
                <p><input type="text" name="sdesc" placeholder="Description" class="form-control"/></p>
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
                <p><input type="text" name="sprice" placeholder="Price" class="form-control"/></p>
                <p><input type="submit" name="submitentry" value="Submit Entry" class="btn btn-primary btn-round"/></p>
        </form>

        <div>
        <center><?php echo $output; ?></center>
        </div>

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