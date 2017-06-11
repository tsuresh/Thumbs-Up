<?php
include("connection.php");
session_start();
if((isset($_SESSION['login_user']))){
	$user = $_SESSION['login_user'];
	//header("location: index.php");	
}else{
	header("location: index.php");
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
			padding:1rem;
			background-color:#FF9C00;
			height:85px;
		}
		.footer{	
			position: absolute;
			right: 0;
			bottom: 0;
			left: 0;
			padding: 1rem;
			background-color:#ffb777;
			height:50px;
			text-align:center;
		}
		
		
		/* Maps API */
		
		/* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        width: 800px;
		height:500px;
      }
      /* Optional: Makes the sample page fill the window. */
      
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }

      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
        /*background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;*/
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
      }
      #target {
        width: 345px;
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
		<div style="width:25%;float:left;">
			<p><img src="logo 2.png" style="width:75px;height:75px;"></p>
		</div>
		
		<div style="width:50%;float:left;margin-top:12px;">
			<form class="form-inline">
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
					
					<input id="pac-input" type="text" class="form-control" placeholder="Search" style="width:300px;">
					
					
					
					
					<input type="submit" class="form-control btn btn-warning" value="SEARCH" name="search" id="search"/>
				</div>
			</form>
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
	
	<div class="mydiv" style="width:100%;margin-top:1%;">
			<div class="mymap" style="width:55%;float:left;margin-left:1%;margin-right:1%;">
				<!--<input id="pac-input" class="controls" type="text" placeholder="Search Box">-->
    <div id="map"></div>
    <script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 6.927079, lng: 79.861244},   
          zoom: 13,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCg1LSL-dDYToIPcpL8NrvtXNXCrggL9yw&libraries=places&callback=initAutocomplete"
         async defer></script>
			
			</div>
			<div class="list" style="width:39%;float:right;background-color:#ffffff;height:500px;margin-left:2%;margin-right:1%;padding:2%;">
			
			<h2>Search Results</h2>
			
      <?php

      include("connection.php");
      $query = $_GET["q"];
      
      if(isset($_GET["submit"])){
        $query = mysqli_query($connection,'SELECT* FROM stores WHERE storename LIKE "%'.$query.'%"');
        while($rows = mysqli_fetch_array($query)){

            $location = $rows["location"];
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$location."&key=AIzaSyDj557b8J3tp4dRWc4vf3kzopf8C6mKScQ";
            $response = file_get_contents( $url, true );

            print_r( $response->results->address_components[1] );

          ?>
          <h4><?php echo $rows ?></h4>
          <?php

        }

      }

      ?>

			</div>
		  </div>
	</div>
	
	<div class="footer">
		<a href="#">ABOUT US</a> | <a href="#">BECOME A CONTRIBUTER</a>
	</div>
	
</body>
</html>