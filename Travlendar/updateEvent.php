<?php include './dbc/databaseconnection.php';

    session_start();

    $title = $_POST['title'];
    $date = $_POST['date'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $address = $_POST['address'];
    $startAddress = $_POST['startAddress'];
    $journey = $_POST['route'];
    $transfer = $_POST['transfer'];
    $passengers = $_POST['passengers'];
    $eco = $_POST['eco'];
    $cost = $_POST['cost'];
    $wet = $_POST['wet'];
    $id = $_POST['eventId'];

    $start = date("H:i:s", strtotime($start));
    $end = date("H:i:s", strtotime($end));

    $return = "";

    $time_before = 0;
    $time_after = 0;

    $sql = ("SELECT * FROM `events` WHERE `user_id` = '" . $_SESSION['userid'] . "'");
    $result = $conn->query($sql);
    $previousDate = '';
    while ($row = $result->fetch_assoc()) {
        if ($row['event_id'] != $id) {
            if ($date === $row['date']) {
                $s = date("H:i:s", strtotime($row['start']) - strtotime($row['journey_time']) - 3600);
                $e = $row['end'];
                $st = date("H:i:s", strtotime($start) - strtotime($transfer) - 3600);
                if (($st < $s && $end > $s) ||
                    ($st < $e && $end > $e) ||
                    ($st > $s && $end < $e)) {
                    $return .= "Collision with event.\n";
                    break;
                } else {
                    $diff = $start - strtotime($row['end']);
                    if ($diff < $time_before && $diff > 0) {
                        $time_before = $diff;
                    }

                    $diff = strtotime($row['start']) - $end;
                    if ($diff < $time_after && $diff > 0) {
                        $time_after = $diff;
                    }
                }
            }
        }
    }

    if ($return !== "") {
        echo $return;
        die();
    }

    $sql = ("SELECT * FROM `flexible_breaks` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $previousDate = '';
    while ($row = $result->fetch_assoc()) {
        $d = $row['duration'];
        $s = $row['start'];
        $e = $row['end'];
        if (($start < $s && $end > $s) ||
            ($start < $e && $end > $e) ||
            ($start > $s && $end < $e)) {

        }
    }

    if ($return !== "") {
        echo $return;
        die();
    }

    $stmt = $conn->prepare("UPDATE
  `events`
SET
  `title` = ?,
  `startadress` = ?,
  `endadress` = ?,
  `date` = ?,
  `start` = ?,
  `end` = ?,
  `passengers` = ?,
  `journey` = ?,
  `journey_time` = ?,
  `eco` = ?,
  `money` = ?,
  `water` = ?
WHERE
  `user_id` = ? AND `event_id` = ?;");

    $stmt->bind_param("ssssssisssssii", $title, $startAddress, $address, $date, $start, $end, $passengers, $journey, $transfer, $eco, $cost, $wet, $_SESSION['userid'], $id);
    $status = $stmt->execute();

    if($status === false){
        $return = "Failed to update event.\n";
    } else {
        $return = "Event updated.\n";
    }

    echo $return;
?>