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
    $eco = $_POST['eco'];
    $cost = $_POST['cost'];
    $wet = $_POST['wet'];
    $passengers = $_POST['passengers'];

    $start = date("H:i:s", strtotime($start));
    $end = date("H:i:s", strtotime($end));

    $return = "";

    $time_before = 0;
    $time_after = 0;

    /**
    * fee spot break
    */
    class OpenSlot
    {        
        public $start;
        public $end;
        public $duration;
        public $break;

        function __construct($s, $e, $d, $b)
        {
            $this->start = $s;
            $this->end = $e;
            $this->duration = $d;
            $this->break = $b;
        }
    }

    $breaks;
    $bChecks;

    $sql = ("SELECT * FROM `flexible_breaks` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $s = $row['start'];
        $e = $row['end'];
        $d = $row['duration'];
        $b = $row['id'];
        $breaks[] = new OpenSlot($s, $e, $d, $b);
        $bChecks[] = $b;
    }

    $sql = ("SELECT * FROM `events` WHERE `user_id` = '" . $_SESSION['userid'] . "' AND `date` = '" . $date . "'");
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $s = date("H:i:s", strtotime($row['start']) - strtotime($row['journey_time']) - 3600);
        $e = date("H:i:s", strtotime($row['end']));
        $st = date("H:i:s", strtotime($start) - strtotime($transfer) - 3600);
        if (($st < $s && $end > $s) ||
            ($st < $e && $end > $e) ||
            ($st >= $s && $end <= $e)) {
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

        foreach ($breaks as $break) {
            if ($s <= $break->start && $e >= $break->end) {
                $return .= "Collision with break.\n";
                break;           
            } elseif ($s <= $break->start && $e > $break->start && $s < $break->end) {
                $break->start = $e;
            } elseif ($s < $break->end && $e >= $break->end && $s > $break->start) {
                $break->end = $s;
            } elseif ($s > $break->start && $e < $break->end) {
                $breaks[] = new OpenSlot($break->start, $s, $break->duration, $break->break);
                $break->start = $e;
            }
        }
    }

    $s = date("H:i:s", strtotime($_POST['start']) - strtotime($_POST['transfer']) - 3600);
    $e = date("H:i:s", strtotime($_POST['end']));

    foreach ($breaks as $break) {
        if ($s <= $break->start && $e >= $break->end) {
            $return .= "Collision with break.\n";
            break;           
        } elseif ($s <= $break->start && $e > $break->start && $s < $break->end) {
            $break->start = $e;
        } elseif ($s < $break->end && $e >= $break->end && $s > $break->start) {
            $break->end = $s;
        } elseif ($s > $break->start && $e < $break->end) {
            $breaks[] = new OpenSlot($break->start, $s, $break->duration, $break->break);
            $break->start = $e;
        }
    }

    foreach ($bChecks as $b) {
        $max = 0;
        foreach ($breaks as $break) {
            $diff = (strtotime($break->end) - strtotime($break->start)) / 60;
            if ($diff >= $break->duration) {
                if ($break->break == $b && $diff > $max) {
                    $max = $diff;
                }
            }
        }
        if ($max == 0) {
            $return .= "Collision with break.\n";
            break;
        }
    }

    if ($return !== "") {
        echo $return;
        die();
    }

    /*
        Put all breaks in a Vector
        Split them from the events in the same date as the created event.
        Split them from the created event.
        Check if there is at east one element which has the time neccessary for the break to exist.
     */

    if ($return !== "") {
        echo $return;
        die();
    }

    $stmt = $conn->prepare("INSERT
INTO
  `events`(
    `user_id`,
    `title`,
    `startadress`,
    `endadress`,
    `date`,
    `start`,
    `end`,
    `passengers`,
    `journey`,
    `journey_time`,
    `eco`,
    `money`,
    `water`
  )
VALUES(
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?,
  ?
);");

    $stmt->bind_param("issssssisssss", $_SESSION['userid'], $title, $startAddress, $address, $date, $start, $end, $passengers, $journey, $transfer, $eco, $cost, $wet);
    $status = $stmt->execute();

    if($status === false){
        $return = "Failed to create event.\n";
    } else {
        $return = "Event created.\n";
    }

    echo $return;
?>