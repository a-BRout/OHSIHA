<?php
require_once __DIR__.'/functions.php';
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
$values = unserialize($_POST['result']);

$user = $_SESSION['username'];

if ($result = $conn->query("SELECT * FROM data WHERE owner = '".$user."'"))
{
    if($result->num_rows == 1)
    {
        echo "Table exists";
    }
    foreach ($values as $row) {
      //$sql = "INSERT INTO data VALUES ($row[0], $row[1], $row[2], $row[3], $row[4], $user)";
      $sql ="INSERT INTO `data`(
    `paivamaara`,
    `litraa`,
    `hinta`,
    `trip`,
    `trip_full`,
    `owner`)
    VALUES
    ('$row[0]', $row[1], $row[2], $row[3], $row[4], '$user')";
      // if ($conn->query($sql) === TRUE)
      // {
      //   echo "New record created successfully";
      // } else
      // {
      //   echo "Error: " . $sql . "<br>" . $conn->error;
      // }
    }
    $url = 'graph.php';
    Redirect($url, $permanent = false);
}
else
{
    echo "Table does not exist";
}
?>
