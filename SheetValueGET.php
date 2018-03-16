<?php

// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
error_reporting(0);
require_once __DIR__.'/testi/vendor/autoload.php';
require_once __DIR__.'/functions.php';


$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $drive = new Google_Service_Sheets($client);

  $spreadsheetId = $_POST['Sheet_ID'];
  $range = $_POST['Sheet_Range'];


  $response = $drive->spreadsheets_values->get($spreadsheetId, $range);
  $values = $response->getValues();

  print "\n";

  // foreach ($values as $row) {
  //   // Print columns A and E, which correspond to indices 0 and 4.
  //   //printf("%s, %s\n", $row[0], $row[4]);
  //   foreach ($row as $key => $value) {
  //     echo  $value. " ";
  //   }
  //   echo  nl2br ("\n");
  // }
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>
<?php if (count($values) > 0):
  $row_names = $values[0];

  if ($row_names < 5 OR $row_names[0] != 'Pvm' OR $row_names[1] != 'Litraa' OR $row_names[2] != 'Euroa' OR $row_names[3] != 'Kilometrit')
  {
    $url = 'instructions.php';
    Redirect($url, $permanent = false);
  }
array_shift($values);
  ?>
<body>
  <form method="post" action="/save_data.php">
    <button type="submit">Data is good</button>
    <input type="hidden" name="result" value="<?php echo htmlspecialchars(serialize($values)); ?>">
  </form>
  <form method="post" action="/SheetChoose.php">
    <button type="submit">Data is not good</button>
  </form>
</body>
<table>
  <thead>
    <tr>
      <th><?php echo implode('</th><th>', array_keys(current($values))); ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($values as $row): array_map('htmlentities', $row); ?>
    <tr>
      <td><?php echo implode('</td><td>', $row); ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>


<?php endif; ?>
