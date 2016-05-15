<?php

//Set result search limit
const searchLimit = 300;
const JServerKey = "AIzaSyB-EX-arCxM0tZFpsXmjBehsF8v-iNLT8A";
$conn;

class Sqli {

    function constructor() {
        
    }

    // Create connection
    public function conn() {        
        global $conn;
        // Connection's Parameters
        $db_host = "localhost";
        $db_name = "nimblyla_jiffyjobs";
        $username = "nimblyla_jjadmin";
        $password = "j!ffyj0bs";
        
        $conn = new mysqli($db_host, $username, $password, $db_name);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    public function close() {
        global $conn;
        $conn->close();
    }
}

?>