<?php
$servername = "localhost";
$username = "viroadmin";
$password = "viroDBADMIN*";
$dbname = "viro2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$serverResources=256;
//nastavim na polnoc dnesku
$todayMidnight = date("Y-m-d H:i:s",strtotime("midnight"));
for ($i = 0; $i<14; $i++){
     $newDate = date("Y-m-d H:i:s",strtotime($todayMidnight.  "+ $i days"));
     for($j=0;$j<6;$j++){
         $hour= $j * 4 ;
         $newHourDate = date("Y-m-d H:i:s",strtotime($newDate.  "+ $hour hours"));

          $sql = "INSERT INTO term (term_date, free_capacity)
          VALUES ( '$newHourDate' , '$serverResources' )";

          if ($conn->query($sql) === TRUE) {
              echo "New record created successfully";
          } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
          }
      }
    }

//DELETE FROM term WHERE term_id NOT IN ( SELECT term_id FROM reservation) AND term_date<CURRENT_DATE
//DELETE OLD AND UNUSED

$delete="DELETE FROM term WHERE term_id NOT IN ( SELECT term_id FROM reservation) AND term_date<CURRENT_DATE";
if ($conn->query($delete) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $delete . "<br>" . $conn->error;
}
$conn->close();
?>
