<?php
session_start();

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'test');

/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = unserialize($_POST['result']);

$user = $_SESSION['username'];

if ($result = $conn->query("SELECT * FROM data WHERE owner = '".$user."'")) {
    if($result->num_rows == 1) {
        echo "Table exists";
    }
}
else {
    echo "Table does not exist";
}

  ?>
