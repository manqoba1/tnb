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



if ($_GET['email'] && $_GET['point']){
    $email = $_GET['email'];
	$point = $_GET['point']; 	
 
    // mysql updating a new row
    $result = mysql_query("UPDATE users SET userState='1' WHERE userID='$point' AND emailAddress='$email'");
	echo mysql_error();
	
    // check if row inserted or not
    if ($result) {			
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Your Account is Activated";

    } else {
        // failed to Update row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred while Activating your Account. Contact electronic-admin@geekulcha.com";
 
        // echoing JSON response
        echo json_encode($response);
    }

	
	

}else{
 // required field is missing
    $response["success"] = 0;
    $response["message"] = "Please enter fill the missing field";
 
    // echoing JSON response
    echo json_encode($response);
}

 
?>