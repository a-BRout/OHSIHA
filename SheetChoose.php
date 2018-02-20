<!-- <form action="/SheetValuesGET.php" method="post">
  Sheet ID:<br>
  <input type="text" name="Sheet_ID"
	pattern="[a-zA-Z0-9-_]+" title="Syötä käypä Google Sheets ID"><br>
  Sheet Range:<br>
  <input type="text" name="Sheet_Range"
	pattern="[a-zA-Z0-9]+![A-Z][1-9]:[A-Z][1-9]+" title="Syötä taulukon nimi ja paikka"><br><br>
	<input type="submit" value="Submit">
</form> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 400px; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Get Table from Google Sheets</h2>
        <form action="/SheetValueGET.php" method="post">
                <label>Sheet ID:</label>
                <input type="text" name="Sheet_ID"class="form-control" pattern="[a-zA-Z0-9-_]+" title="Get your Google Sheet ID">
                <br><br>
                <label>Sheet Range:</label>
                <input type="text" name="Sheet_Range" class="form-control" 	pattern="[a-zA-Z0-9]+![A-Z][1-9]:[A-Z][1-9]+" title="Example: Sheet1:A1:G33">
                <br><br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            <p>Wrong place? <a href="start_page.php">Click here</a>.</p>
            </div>
        </form>
    </div>
</body>
</html>
