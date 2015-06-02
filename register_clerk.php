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
$clerk = $json_results->clerk;

if ($clerk->name && $clerk->surname && $clerk->email && $clerk->password) {

    $name = $clerk->name;
    $surname = $clerk->surname;
    $email = $clerk->email;
    $password = $clerk->password;
    $municipalityID = $clerk->municipalityID;
    $wardsID = $clerk->wardsID;


//Let's check if the user is already registered
    $checkemail = mysql_query("select * from clerk where email='$email' ");
    if (mysql_num_rows($checkemail) > 0) {

        echo mysql_num_rows($checkemail);
        $response["success"] = 0;
        $response["message"] = "The email address has been registered already";

        // echoing JSON response
        echo json_encode($response);
    } else {

        // mysql inserting a new row
        if ($municipalityID > 0) {
            $result = mysql_query("INSERT INTO clerk(name,surname,email,password,municipalityID) 
						   VALUES('$name','$surname', '$email','$password','$municipalityID')");
            echo mysql_error();
        } else if ($wardsID > 0) {
            $result = mysql_query("INSERT INTO clerk(name,surname,email,password,wardsID) 
						   VALUES('$name','$surname', '$email','$password','$wardsID')");
            echo mysql_error();
        }

        // check if row inserted or not
        if ($result) {
            $result_pa = mysql_query("SELECT * FROM clerk WHERE email = '$email'");
            if (mysql_num_rows($result_pa) > 0) {

                $row = mysql_fetch_array($result_pa);

                $clerk = array();
                $clerk["patientID"] = $row["patientID"];
                $clerk["firstName"] = $row["firstName"];
                $clerk["middleName"] = $row["middleName"];
                $clerk["lastName"] = $row["lastName"];
                $clerk["phoneNumber"] = $row["phoneNumber"];
                $clerk["email"] = $row["email"];
                $clerk["password"] = $row["password"];
                $clerk["municipalityID"] = $row["municipalityID"];
                $clerk["wardsID"] = $row["wardsID"];

                // users node
                $response["clerk"] = array();

                $response["success"] = 1;
                $response["message"] = "Registration successful, Please Check Your Email.";

                // patient
                array_push($response["clerk"], $clerk);

                // echoing JSON response
                echo json_encode($response);
            } else {
                $response["success"] = 0;
                $response["message"] = "Clerk Not Found";

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