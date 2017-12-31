<?php include './dbc/databaseconnection.php';
    include 'secureinput.php';

    session_start();

    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
    }

    $events = 'No events.';

    $today = date('Y-m-d');
    $tomorrow = new DateTime('tomorrow');
    $tomorrow = $tomorrow->format('Y-m-d');

    $sql = ("SELECT * FROM `events` WHERE `user_id` = '" . $_SESSION['userid'] . "'  AND `date` >= '" . $today . "' ORDER BY `date`, `start`;");
    $result = $conn->query($sql);
    $previousDate = '';
    while ($row = $result->fetch_assoc()) {
        if($events === 'No events.'){
            $events = '';
        }

        if($row['date'] != $previousDate){
            if ($row['date'] == $today) {
                $events .= '
                </div>
                <div class="day">
                        <h1>Today</h1>
                        <hr>';
            } elseif ($row['date'] == $tomorrow) {
                $events .= '
                </div>
                <div class="day">
                        <h1>Tomorrow</h1>
                        <hr>';
            } else {
                $events .= '
                </div>
                <div class="day">
                        <h1>' . date('l, jS \of F Y', strtotime($row['date'])) . '</h1>
                        <hr>';
            }

            $previousDate = $row['date'];
        }

        $events .= createEvent($row['event_id'], $row['title'], $row['start'], $row['end'], $row['speed'], $row['eco'], $row['money'], $row['water'], $row['journey']);
    }

    $events .= '
    </div>';

    $today = date('l, jS \of F Y');

    function createEvent($id, $title, $from, $to, $speed, $eco, $money, $water, $journey){
    return '
                    <div class="event">
                        <div class="especs">
                            <p class="eventTitle">
                                ' . $title . ', ' . date('H:i', strtotime($from)) . ' - ' . date('H:i', strtotime($to)) . '
                            </p>
                            <div class="labels">
                                <div class="label">
                                    <img src="icons/urgency.svg">
                                    <p>' . $speed . '</p>
                                </div>
                                <div class="label">
                                    <img src="icons/recycling.svg">
                                    <p>' . $eco . '</p>
                                </div>
                                <div class="label">
                                    <img src="icons/money.svg">
                                    <p>' . $money . '</p>
                                </div>
                                <div class="label">
                                    <img src="icons/drop.svg">
                                    <p>' . $water . '</p>
                                </div>
                            </div>
                        </div>
                        <div class="eNav">
                            <a target="_blank" href="https://www.google.com/maps/dir/?api=1&' . $journey . '"><img src="./icons/paper-plane.svg"></a>
                            <a href="./edit.php?event=' . $id . '"><img src="./icons/edit.svg"></a>
                        </div>
                    </div>';
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>Travlendar+</title>
        <link rel="stylesheet" type="text/css" href="./style.css">
    </head>
    <body>
        <header>
            <h1><?=$today?></h1>
            <div id="icmenu">
                <a href="./create.php"><img src="icons/add.svg"></a>
                <a href="./settings.php"><img src="icons/settings.svg"></a>
                <a href="./logout.php"><img src="icons/exit.svg"></a>
            </div>
        </header>
        <main>
            <section id="eventlist">
                <?=$events?>
            </section>
        </main>
    </body>
</html>
