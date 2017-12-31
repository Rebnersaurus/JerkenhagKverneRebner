<?php include './dbc/databaseconnection.php';

    session_start();

    $mode = $_POST['mode'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $arrival = $_POST['arrival'];

    $from = str_replace(' ', '%20', $from);
    $from = str_replace(',', '%2C', $from);

    $to = str_replace(' ', '%20', $to);
    $to = str_replace(',', '%2C', $to);

    $sb = false;

    if ($mode == "sb") {
    	$mode = "WALKING";
    	$sb = true;
    }

    $j = 'origin=' . $from . '&destination=' . $to . '&travelmode=' . $mode - '&arrival_time=' . $arrival;


    $j = 'origin=' . $from . '&destination=' . $to . '&mode=' . $mode . '&arrival_time=' . $arrival;

    $call = file_get_contents("https://maps.googleapis.com/maps/api/directions/xml?" . $j . "&key=AIzaSyBtn0qzjWh6CsQiP8j7VfXicKQNQeYsLK8");

    $xml = simplexml_load_string($call);

    $return = '';

    if ($xml->status != "OK") {
        echo $return;
        die();
    }

    if ($mode == "walking") {
        $modeOfTransport = "Walking";
    } elseif ($mode == "bicycling") {
        $modeOfTransport = "Bicycling";
    } elseif ($mode == "driving") {
        $modeOfTransport = "Driving";
    } elseif ($mode == "transit") {
        $modeOfTransport = "Transit";
    }

    $time = $xml->route->leg->duration->value;
    $distance = $xml->route->leg->distance->value + 0.000001;

    $startLat = $xml->route->leg->start_location->lat->value;
    $startLng = $xml->route->leg->start_location->lng->value;
    $endLat = $xml->route->leg->end_location->lat->value;
    $endLng = $xml->route->leg->end_location->lng->value;

    $walking = 0;
    $biking = 0;
    $co2 = 0;
    $money = 0;
    $water = 0;

    $ticket = '';

    foreach ($xml->route->leg->step as $path) {
    	if ($path->travel_mode == "WALKING") {
    		$walking += $path->distance->value;
    		$water += ($path->distance->value * $path->duration->value)/100;
    	} else if ($path->travel_mode == "BICYCLING") {
    		$biking += $path->distance->value;
    		$water += ($path->distance->value * $path->duration->value)/100;
    	} else if ($path->travel_mode == "DRIVING") {
    		$co2 += ($path->distance->value * $path->duration->value)/100;
    		$money += ($path->distance->value * $path->duration->value)/100;
    	} else if ($path->travel_mode == "TRANSIT") {
            $money = 1000;
            if ($path->transit_details->line->vehicle->type == "BUSS") {
                $co2 += ($path->distance->value * $path->duration->value)/500;
                $ticket = 'Ticket: <a href="https://www.atm.it/en/ViaggiaConNoi/Biglietti/Pages/Tipologie.aspx">ATM</a>';
            } else if ($path->transit_details->line->vehicle->type == "TRAM") {
                $co2 += ($path->distance->value * $path->duration->value)/1000;
                $ticket = 'Ticket: <a href="https://www.atm.it/en/ViaggiaConNoi/Biglietti/Pages/Tipologie.aspx">ATM</a>';
            } else if ($path->transit_details->line->vehicle->type == "SUBWAY") {
                $co2 += ($path->distance->value * $path->duration->value)/1000;
                $ticket = 'Ticket: <a href="https://www.atm.it/en/ViaggiaConNoi/Biglietti/Pages/Tipologie.aspx">ATM</a>';
            } else if ($path->transit_details->line->vehicle->type == "TRAIN") {
                $co2 += ($path->distance->value * $path->duration->value)/1000;
            }
        }
    }

    //$return .= "time=$time\n";
    //$return .= "distance=$distance\n";
    //$return .= "walkingDistance=$walking\n";
    //$return .= "bikeDistance=$biking\n";
    //$return .= "wetness=\n";
 	
 	$bike = '';
    if($biking > 0){
    	$bike = '<p>Biking: ' . $biking . ' m</p>';
    }

    $eco = '';
    if($co2 / $distance > 5){
    	$eco = 'High';
    } else if ($co2 / $distance > 3) {
    	$eco = 'Medium';
    } else if ($co2 / $distance > .1) {
    	$eco = 'Low';
    } else {
    	$eco = 'None';
    }

    $cost = '';
    if($money / $distance > 5){
    	$cost = 'High';
    } else if ($money / $distance > 3) {
    	$cost = 'Medium';
    } else if ($money / $distance > .1) {
    	$cost = 'Low';
    } else {
    	$cost = 'None';
    }

    $wet = '';
    if($water / $distance > 5){
    	$wet = 'High';
    } else if ($water / $distance > 3) {
    	$wet = 'Medium';
    } else if ($water / $distance > .1) {
    	$wet = 'Low';
    } else {
    	$wet = 'None';
    }

    $sql = ("SELECT * FROM `relevances` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $previousDate = '';
    $row = $result->fetch_assoc();

    function getRel($rel){
        if ($rel == "high") {
            return 3;
        } elseif ($rel == "medium") {
            return 2;
        } elseif ($rel == "low") {
            return 1;
        } else {
            return 0;
        }
    }

    $relevance = ($co2 * getRel($row['carbon']) + $money * getRel($row['price']) + $water * getRel($row['dryness']) + $time * getRel($row['speed'])) / $distance * 10;

    $return .= '<div class="journey">
                        <h2>' . $modeOfTransport . '</h2>
                        <div class="jspecs">
                            <p>Time: <span class="rTime">' . gmdate("H:i:s", intval($time)) . '</span></p>
                            <p>Distance: ' . round($distance/1000, 2) . ' km</p>
                            <p>Walking: ' . $walking . ' m</p>
                            ' . $bike . '
                            ' . $ticket . '
                            <div class="labels">
                                <div class="label">
                                    <img src="icons/recycling.svg">
                                    <p class="rEco">' . $eco . '</p>
                                </div>
                                <div class="label">
                                    <img src="icons/money.svg">
                                    <p class="rCost">' . $cost . '</p>
                                </div>
                                <div class="label">
                                    <img src="icons/drop.svg">
                                    <p class="rWet">' . $wet . '</p>
                                </div>
                            </div>
                            <p>Relevance: ' . round($relevance) . '</p>
                            <input type="radio" name="route" value="' . $j . '">
                       	</div>
                    </div>';

    echo $return;
?>