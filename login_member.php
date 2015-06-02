<?php

/*
 * Following code will get single user details
 * A user is userIDentified by user userID (puserID)
 */

// array for JSON response
$response = array();
// check for require fields
// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$json_results = json_decode($_GET["json"]);
// check for post data
if ($json_results->email && $json_results->password) {
    //$email = $json_results->email;
    $password = $json_results->password;
    $email = $json_results->email;
$encrypt=md5($password);
    // get a patient from user table
    $result = mysql_query("SELECT * FROM communitymember WHERE email = '$email' && password = '$encrypt'");

    if (mysql_num_rows($result) > 0) {


        $row = mysql_fetch_array($result);

        $communityMember = array();
        $communityMember["communityMemberID"] = $row["communityMemberID"];
        $communityMember["name"] = $row["name"];
        $communityMember["cell"] = $row["cell"];
        $communityMember["email"] = $row["email"];
        $communityMember["municipalityID"] = $row["municipalityID"];

        // success
        $response["success"] = 1;
        $response["message"] = "community Member successfully sign up";
        // users node
        $response["communityMember"] = array();

        // patient
        array_push($response["communityMember"], $communityMember);

        // echoing JSON response
        echo json_encode($response);
    } else {
        // no user found
        $response["success"] = 0;
        $response["message"] = "Incorrect Email or Password.";

        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>