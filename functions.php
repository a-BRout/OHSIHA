<?php
function Redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}

function Get_table($user)
{
  define('DB_SERVER', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '');
  define('DB_NAME', 'test');

  $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  $sql = "SELECT `paivamaara`, `litraa`, `hinta`, `trip`, `trip_full` FROM `data` WHERE `owner` = '".$user."'";
  $result = array();

  if ($stmt = $conn->prepare($sql)) {

      /* execute statement */
      $stmt->execute();

      /* bind result variables */
      $stmt->bind_result($paivamaara, $litraa, $hinta, $trip, $tripfull);

      /* fetch values */
      while ($stmt->fetch()) {
        $row = array();
        $row = [$paivamaara, $litraa, $hinta, $trip, $tripfull];
        $result[] = $row;
      }

      /* close statement */
      $stmt->close();
  }
  return $result;
}
?>
