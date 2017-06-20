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
<<<<<<< HEAD
//Delete old files
$base="/opt/viro2/topology";
$sql_del="SELECT topo_name,name FROM topology,users_field_data WHERE topology_id IN (SELECT topology_id FROM reservation WHERE saved_until<='2017-06-17 20:00:00') AND uid IN (SELECT user_id FROM reservation WHERE saved_until<='$todayMidnight')";
$dir_del = $conn->query($sql_del);
while($record = $dir_del->fetch_assoc()){
  $topo_name = str_replace(' ','_',$record["topo_name"]);
  exec('rm -rf '.$base.'/'.$topo_name.'/'.$record["name"].' &');
}
=======

>>>>>>> master
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
