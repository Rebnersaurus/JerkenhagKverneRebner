<?php include './dbc/databaseconnection.php';

  $html = file_get_contents('https://www.bikemi.com/en/stations-map.aspx');

  preg_match_all("/45.([0-9]+), 9.([0-9]+), '([A-Za-z0-9 -.àù\\/\"']+)'/", $html, $matches);
  
  preg_match_all("/(?!u003e)([0-9]+\\)/", $html, $bikes);

  $sql = ("SELECT * FROM `bike_mi` WHERE 1");
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    for ($i=0; $i < sizeof($matches[0]); $i++) { 
      if (strpos($matches[0][$i], $name) !== false) {
        $a = explode(',', $matches[0][$i]);
        $avl = $bikes[$i * 4];
        $slt = $bikes[$i * 4 + 1];
        $sql = ("UPDATE `bike_mi` SET 
          `longitude` = " . $a[0] . ", 
          `latitude` = " . $a[1] . ",
          `available_bikes` = " . substr($avl, 0, -1) . ",
          `available_slots` = " . substr($slt, 0, -1) . "
           WHERE 
           `name` = '" . $name . "'");
        $conn->query($sql);

        print_r($avl);
      }
    }
  }

  print_r($matches[0][0]);
?>