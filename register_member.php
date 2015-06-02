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
$json_results = json_decode($_GET["json"]);
$communityMember = $json_results->json_results;

if ($communityMember->municipalityID && $communityMember->email) {

    $municipalityID = $communityMember->municipalityID;
    $name = $communityMember->name;
    $cell = $communityMember->cell;
    $email = $communityMember->email;
    $password = $communityMember->password;
	$encrypt=md5($password);
    $checkemail = mysql_query("SELECT * FROM communitymember WHERE email = '$email'");
    if (mysql_num_rows($checkemail) > 0) {

        // required field is missing
        echo mysql_num_rows($checkemail);
        $response["success"] = 0;
        $response["message"] = "The email address has been registered already";

        // echoing JSON response
        echo json_encode($response);
    } else {
        $result = mysql_query("INSERT INTO communitymember(name,cell,email,password,municipalityID) 
						   VALUES('$name','$cell', '$email','$encrypt','$municipalityID')");
        echo mysql_error();

        // check if row inserted or not
        if ($result) {

            $result_pa = mysql_query("SELECT * FROM communitymember WHERE email = '$email'");

            if (mysql_num_rows($result_pa) > 0) {

                $row = mysql_fetch_array($result_pa);

                $communityMember = array();
                $communityMember["communityMemberID"] = $row["communityMemberID"];
                $communityMember["name"] = $row["name"];
                $communityMember["cell"] = $row["cell"];
                $communityMember["email"] = $row["email"];
                $communityMember["password"] = $row["password"];
                $communityMember["municipalityID"] = $row["municipalityID"];

                // users node
                $response["communityMember"] = array();

                // patient
                array_push($response["communityMember"], $communityMember);

                $response["success"] = 1;
                $response["message"] = "Registration successful, Please Check Your Email.";
                // echoing JSON response
                echo json_encode($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Registration Not successful";

            // echoing JSON response
            echo json_encode($response);
        }
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Please enter all information";

    // echoing JSON response
    echo json_encode($response);
}
?>