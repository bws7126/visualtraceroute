<?php $site = (isset($_POST["website"]) ? $_POST["website"] : ""); ?>

<?php include "../inc/dbinfo.inc"; ?>

<form action='test.php' method='post'><input type='text' name='website' value='<?php echo $site; ?>' /><input type='submit' value='Visualize' class='button' /></form>

<?php

if ($site != "") {
	$output = shell_exec("traceroute $site -n");

	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

	// Check connection
	if($link === false){
	    die("ERROR: Could not connect. " . mysqli_connect_error());
	}

	 mysqli_query($link, "TRUNCATE TABLE ipaddresses");
	 
	// Close connection
	mysqli_close($link);

	$lines = explode("\n", $output);

	$hopNumber = 0;
	foreach ($lines as $line) {
	if (stristr($line, "traceroute")) {
		echo $line;
		echo "<br /><br />";
		continue;
	}

	$traceData = explode(" ", $line);

	//echo "<pre>".print_r($data, true)."</pre>";

	$ipAddr = "";
	$milliseconds = "";
	foreach ($traceData as $key => $data) {
		if (strstr($data, ".")) {
			if ($traceData[$key + 1] == "ms") {
				$milliseconds = $data."ms";
			}
			else {
				$ipAddr = $data;
			}
		}
	}

	$hopNumber++;

	if (($hopNumber == count($lines) - 1)) {
		break;
	}

	if ($ipAddr == "" && $milliseconds == "") {
		echo $hopNumber." *";
		echo "<br /><br />";
		continue;

	}
		
	$ip_address = $ipAddr;           
	$location = file_get_contents('http://freegeoip.net/json/'.$ip_address);
	$obj = json_decode($location);
	$latitude = $obj->{'latitude'};
	$longitude = $obj->{'longitude'};
	//echo $latitude;
	//echo $longitude;

	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

	// Check connection
	if($link === false){
	    die("ERROR: Could not connect. " . mysqli_connect_error());
	}

	// Attempt insert query execution
	$sql = "INSERT INTO ipaddresses (hop, ip, ms, lat, lng) VALUES ('$hopNumber', '$ipAddr', '$milliseconds', '$latitude', '$longitude')";
	if(!mysqli_query($link, $sql)){
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	 
	// Close connection
	mysqli_close($link);


		echo $hopNumber." ".$ipAddr." ".$milliseconds;
		echo "<br /><br />";
	}
}



