<?php

error_reporting(0);

header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","","thumbsup");

$task = $_GET["task"];

if($task == "getdetail"){

    $id = $_GET["rid"];

    if(!empty($id)){

        $id = mysqli_real_escape_string($conn,$id);

        //basic details
        $dataquery = mysqli_query($conn,"SELECT id, name, pic, price, description FROM items WHERE rand='$id';");
        $datarow = mysqli_fetch_array($dataquery);
        $itemid = $datarow["id"];


        //getting the reviews
        $rvquery = mysqli_query($conn,"SELECT uname, quality, taste, price, description FROM reviews WHERE itemid='$itemid';");
        $rvarray = array();

        $quality = "0.0";
        $taste = "0.0";
        $price = "0.0";        

        $rvrows = mysqli_num_rows($rvquery);

        while($rvrow = mysqli_fetch_array($rvquery)){
            $rvarray[] = $rvrow;

            $quality = $quality + $rvrow["quality"];
            $taste = $taste + $rvrow["taste"];
            $price = $price + $rvrow["price"];
        }

        if($rvrows>0){
            $taste = $taste/$rvrows;
            $quality = $quality/$rvrows;
            $price = $price/$rvrows;
        }

        $finalarray = array(
            "taste"     =>  $taste,
            "price"     =>  $price,
            "quality"   =>  $quality,
            "details"   =>  $datarow,
            "reviews"   =>  $rvarray
        );

        $encoded = json_encode($finalarray);
        echo $encoded;

    } else {
        echo "ERROR";
    }

} else if($task == "addrating") {

    $return = array(
        "message"   =>  "OK"
    );

    $name = $_GET["name"];
    $uid = $_GET["uid"];
    $itemid = $_GET["itemid"];

    $price = $_GET["price"];
    $quality = $_GET["quality"];
    $taste = $_GET["taste"];
    $description = $_GET["description"];

    $name = mysqli_real_escape_string($conn,$name);
    $description = mysqli_real_escape_string($conn,$description);

    if($price || $quality || $taste || $description){
        
        if(!empty($description)){
            mysqli_query($conn,"INSERT INTO `thumbsup`.`points` (`id`, `itemid`, `userid`, `points`, `datetime`) VALUES (NULL, '$itemid', '$uid', '20', NOW())");
        } else {
            mysqli_query($conn,"INSERT INTO `thumbsup`.`points` (`id`, `itemid`, `userid`, `points`, `datetime`) VALUES (NULL, '$itemid', '$uid', '5', NOW())");
        }

        mysqli_query($conn,"INSERT INTO `reviews` (`id`, `itemid`, `uname`, `uid`, `quality`, `taste`, `price`, `description`) VALUES (NULL, '$itemid', '$name', '$uid', '$quality', '$taste', '$price', '$description');");
        $return["message"] = "success";

    } else {
        $return["message"] = "At least one field is required!";
    }

    echo json_encode($return);

} if($task == "login"){

	$username = $_GET["username"];
	$password = $_GET["password"];
	
	$status = array();
	
	if(!empty($username) && !empty($password)){
		
		//filter values
		$username = mysqli_real_escape_string($conn,$username);
		$password = md5($password);
		
		//match with DB
		$query = mysqli_query($conn,"SELECT* FROM users WHERE email='$username'");
		$rows = mysqli_num_rows($query);
		
		if($rows == 1){
			
			$data = mysqli_fetch_array($query);
			
			if($data["password"] == $password){
						
					//Update security hash
					$hash = md5(time().$username.$password);
					
					mysqli_query($conn,"UPDATE users SET hash='$hash' WHERE username='$username';");
					
					$status["message"] = "success";
					$status["hash"] = $hash;
					$status["fullname"] = $data["name"];
					$status["uid"] = $data["id"];
				
			} else {
				$status["message"] = "Incorrect Password!";	
			}
			
		} else {
			$status["message"] = "User doesn't exist!";	
		}
		
	} else {
		$status["message"] = "Please enter your username and password!";	
	}

    echo json_encode($status);
	
} else if($task == "register"){
	
	$status = array(
		"message"	=>	""
	);
	
	$fname = $_GET["fname"];
	$email = $_GET["username"];
	$password = $_GET["password"];
	
	if(!empty($fname) && !empty($email) && !empty($password)){
		
		$fname = mysqli_real_escape_string($conn,$fname);
		$email = mysqli_real_escape_string($conn,$email);
		$password = md5($password);
		$hash = md5(time().$email.$password);
		
		//match with DB
		$query = mysqli_query($conn,"SELECT* FROM users WHERE email='$email'");
		$rows = mysqli_num_rows($query);
		
		if($rows == 0){
			
			//registration possible
			mysqli_query($conn,"INSERT INTO `thumbsup`.`users` (`id`, `name`, `email`, `password`, `acctype`, `hash`) VALUES (NULL, '$fname', '$email', '$password', 'normal', '$hash');");
			$status["message"] = "success";
			
		} else {
			$status["message"] = "User account already exists!";	
		}
		
	} else {
		$status["message"] = "All fields are required!";
	}

    echo json_encode($status);
} else if($task == "storelist"){

    $query = mysqli_query($conn,"SELECT storename, location FROM stores");

    $array = array();

    while($row = mysqli_fetch_array($query)){
        $array[] = $row;
    }

    $farray = array(
        "stores"    =>  $array
    );

    echo json_encode($farray);

} else if($task == "topitems"){

    $query = mysqli_query($conn,"SELECT name, description FROM items");

    $rvarray = array();

    while($row = mysqli_fetch_array($query)){

        $rvarray[] = $row;
        
    }

    $finalarray = array(
        "items" =>  $rvarray
    );

    echo json_encode($finalarray);

} else if($task == "getprofile"){

    $uid = $_GET["uid"];

    $pointstotq = mysqli_query($conn,"SELECT* FROM points WHERE userid='$uid'");
    $total = 0;

    while($data = mysqli_fetch_array($pointstotq)){
        $total = $total + $data["points"];
    }

    $rvquery = mysqli_query($conn,"SELECT itemid, uname, quality, taste, price, description FROM reviews WHERE uid='$uid';");
    $rvarray = array();

    $quality = "0.0";
    $taste = "0.0";
    $price = "0.0";        

    while($rvrow = mysqli_fetch_array($rvquery)){
        $quality = $quality + $rvrow["quality"];
        $taste = $taste + $rvrow["taste"];
        $price = $price + $rvrow["price"];
        $overall = ($quality+$taste+$price)/3;
        $description = $rvrow["description"];
        $name = $rvrow["uname"];
        $id = $rvrow["itemid"];

        //get item detail
        $dataq = mysqli_query($conn,"SELECT name FROM items WHERE id='$id';");
        $daddd = mysqli_fetch_array($dataq);

        $foodname = $daddd["name"];


        $rvarray[] = array(
            "overall"   =>  $overall,
            "description"   =>  $foodname.":- ".$description,
            "name"  =>  $name,
            "foodname"  =>  $foodname
        );
    }

    $finalarray = array(
        "totalpoints"   =>  $total,
        "reviews"   =>  $rvarray
    );

    echo json_encode($finalarray);

} else if($task == "offerslist"){

    $ofq = mysqli_query($conn,"SELECT* FROM offers ORDER BY id ASC");
    
    $lol = array();

    while($row = mysqli_fetch_array($ofq)){
        $lol[] = $row;
    }

    $finalarray = array(
        "offers"    =>  $lol
    );

    echo json_encode($finalarray);

}


?>