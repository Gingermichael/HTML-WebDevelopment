
<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>Contact Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
</head>
<body>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the C_Responses table exists. */
  VerifyC_ResponsesTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the EMPLOYEES table. */
  $C_name = htmlentities($_POST['NAME']);
  $C_email = htmlentities($_POST['EMAIL']);
  $C_subject = htmlentities($_POST['SUBJECT']);
  $C_message = htmlentities($_POST['MESSAGE']);

  if (strlen($C_name) || strlen($C_email) || strlen($C_subject) || strlen($C_message)) {
    AddC_Responses($connection, $C_name, $C_email, $C_subject, $C_message);
  }
?>

<div class="panel-body">
    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <div class="form-group">
        <label for="NAME">Full Name</label>
        <input
            type="text"
            class="form-control"
            id="NAME"
            name="NAME"
            maxlength="45" 
            size="30"
        />
        </div>
        <div class="form-group">
        <label for="EMAIL">Email</label>
        <input
            type="text"
            class="form-control"
            id="EMAIL"
            name="EMAIL"
            maxlength="45" 
            size="30"
        />
        </div>
        <div class="form-group">
        <label for="SUBJECT">Subject</label>
        <input
            type="text"
            class="form-control"
            id="SUBJECT"
            name="SUBJECT"
            maxlength="45" 
            size="30"
        />
        </div>
        <div class="form-group">
        <label for="MESSAGE">Message</label>
        <input
            type="text"
            class="form-control"
            id="MESSAGE"
            name="MESSAGE"
            maxlength="300" 
            size="90"
        />
        </div>
        <input type="submit" class="btn btn-primary" value="Add Data"/>
    </form>
    </div>

<!-- Display table data. -->
<table class="table" border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>EMAIL</td>
    <td>SUBJECT</td>
    <td>MESSAGE</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM C_Responses");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add a C_Response to the table. */
function AddC_Responses($connection, $name, $email, $subject, $message) {
   $n = mysqli_real_escape_string($connection, $name);
   $e = mysqli_real_escape_string($connection, $email);
   $s = mysqli_real_escape_string($connection, $subject);
   $m = mysqli_real_escape_string($connection, $message);

   $query = "INSERT INTO C_Responses (NAME, EMAIL, SUBJECT, MESSAGE) VALUES ('$n', '$e', '$s', '$m');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding response data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyC_ResponsesTable($connection, $dbName) {
  if(!TableExists("C_Responses", $connection, $dbName))
  {
     $query = "CREATE TABLE C_Responses (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         EMAIL VARCHAR(45),
         SUBJECT VARCHAR(45),
         MESSAGE TEXT(300)
       );";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>      