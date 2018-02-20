<?php
error_reporting(0);
require_once __DIR__.'/testi/vendor/autoload.php';

session_start();

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
<?php if (count($values) > 0): ?>
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