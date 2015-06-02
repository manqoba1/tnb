<?php
 
/*
 * Following code will create a new user row
 * All user details are read from HTTP Post Request
 */
 
// array for JSON response
$response = array();
	// include db connect class
    require_once __DIR__ . '/db_connect.php';
 
    // connecting to db
    $db = new DB_CONNECT();

// check for require fields
$json_results = json_decode($_GET["json"]);;

if ($json_results->email) {
   
    $email = $json_results->email;   

	//Let's check if the user is already registered
	$checkemail = mysql_query("select * from clerk where email='$email' ");
	if(mysql_num_rows($checkemail)  < 1) {
	 
		$response["success"] = 0;
		$response["message"] = "The email address is not registered";
	 
		// echoing JSON response
		echo json_encode($response);
	}
	else { 
		$row=mysql_fetch_array($checkemail);
		$name = $row["name"];
		//Creating new password
		$password = rand(0,9).rand(20, 90).rand(0,9).rand(0,9).rand(0,9).rand(20, 70);
	
		//encrypting the password
		$encrypt=md5($password);
 
		// mysql update the password
		$result = mysql_query("update clerk set password='$encrypt' where email='$email'");
		
	  
		// check if row inserted or not
		if ($result) {
			// successfully inserted into database
			$response["success"] = 1;
			$response["message"] = "Password recovered.";

			 $RegMessage='
				  <html>
				  <body style="background: orange; color: grey;">
				<div style="margin-left: 20px;">
				  <img src="http://app.chowpos.com/chowpos/images/logo.png" align="center" style="height: 250px; width: 300px;"/><br />
				   <h2>ChowPos - What Are You Craving?</h2>
				  <b>Hi '.$name.'</b></br /><br />
				  You have indicated that you have forgotten your password. If not please alert the Admin to tnb-admin@tnb.com . Your password has been reset to '.$password.' please login into the app and change it.
				  <br /><br />

	13 Balfour Street<br />

	Nellmapius<br />

	Pretoria<br />

	0122<br />


	&#169; The Notice Board 2014 property of Codetribe (Pty) Ltd
		</div>          
				  </body>
				  </html>
				  ';

//tnb-admin@tnb.com dont forget to include it below after creating it
			 $headers = "From: " . strip_tags($email) . "\r\n";
				   $headers .= "Reply-To: ". strip_tags($email) . "\r\n";
				   $headers .= "MIME-Version: 1.0\r\n";
				   $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	   
				   $mail = mail($email, "GKElectronics Forgotten Password", $RegMessage, $headers);
	 
        // echoing JSON response
        echo json_encode($response);

		} else {
			// failed to insert row
			$response["success"] = 0;
			$response["message"] = "Oops! An error occurred while registering you. Contact tnb-admin@tnb.com";
	 
			// echoing JSON response
			echo json_encode($response);
		}
	}
} 
else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Please enter all information";
 
    // echoing JSON response
    echo json_encode($response);
}
 
?>