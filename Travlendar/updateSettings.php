<?php include './dbc/databaseconnection.php';

    session_start();

    $address = $_POST['address'];
    $relevances = $_POST['relevanceValues'];
    $distances = $_POST['distanceValues'];
    $available = $_POST['availableMoTValues'];
    $startTimes = $_POST['startTimeValues'];
    $stopTimes = $_POST['stopTimeValues'];
    $freePeriods = $_POST['freePeriodsValues'];
    $freeFroms = $_POST['freeFromValues'];
    $freeTos = $_POST['freeToValues'];

    $return = "";

    $stmt = $conn->prepare("UPDATE
  `users`
SET
  `home` = ? 
WHERE
  `id` = ?");

    $stmt->bind_param("si", $address, $_SESSION['userid']);
    $status = $stmt->execute();

    if($status === false){
        $return .= "Failed to update home address.\n";
    }

    $stmt = $conn->prepare("UPDATE
  `relevances`
SET
  `dryness` = ?,
  `price` = ?,
  `carbon` = ?,
  `speed` = ?
WHERE
  `user_id` = ?");

    $stmt->bind_param("ssssi", $relevances[0], $relevances[1], $relevances[2], $relevances[3], $_SESSION['userid']);
    $status = $stmt->execute();

    if($status === false){
        $return .= "Failed to update relevances.\n";
    }

    $stmt = $conn->prepare("UPDATE
  `max_distances`
SET
  `max_walk` = ?,
  `max_bike` = ?
WHERE
  `user_id` = ?");

    $stmt->bind_param("iii", $distances[0], $distances[1], $_SESSION['userid']);
    $status = $stmt->execute();

    if($status === false){
        $return .= "Failed to update distances.\n";
    }

    $stmt = $conn->prepare("UPDATE
  `means_of_transports`
SET
  `available_walk` = ?,
  `earliest_walk` = ?,
  `latest_walk` = ?,
  `available_bike` = ?,
  `earliest_bike` = ?,
  `latest_bike` = ?,
  `available_shared_bike` = ?,
  `earliest_shared_bike` = ?,
  `latest_shared_bike` = ?,
  `available_car` = ?,
  `earliest_car` = ?,
  `latest_car` = ?,
  `available_shared_car` = ?,
  `earliest_shared_car` = ?,
  `latest_shared_car` = ?,
  `available_bus` = ?,
  `earliest_bus` = ?,
  `latest_bus` = ?,
  `available_tram` = ?,
  `earliest_tram` = ?,
  `latest_tram` = ?,
  `available_metro` = ?,
  `earliest_metro` = ?,
  `latest_metro` = ?,
  `available_train` = ?,
  `earliest_train` = ?,
  `latest_train` = ?,
  `available_taxi` = ?,
  `earliest_taxi` = ?,
  `latest_taxi` = ?
WHERE
  `user_id` = ?");

    $stmt->bind_param("ississississississississississi", 
        $available[0], $startTimes[0], $stopTimes[0], 
        $available[1], $startTimes[1], $stopTimes[1], 
        $available[2], $startTimes[2], $stopTimes[2], 
        $available[3], $startTimes[3], $stopTimes[3], 
        $available[4], $startTimes[4], $stopTimes[4], 
        $available[5], $startTimes[5], $stopTimes[5], 
        $available[6], $startTimes[6], $stopTimes[6], 
        $available[7], $startTimes[7], $stopTimes[7], 
        $available[8], $startTimes[8], $stopTimes[8], 
        $available[9], $startTimes[9], $stopTimes[9],
        $_SESSION['userid']);
    $status = $stmt->execute();

    if($status === false){
        $return .= "Failed to update transportations.\n";
    }

    $stmt = $conn->prepare("UPDATE
  `public_transports`
SET
  `free_period_shared_bike` = ?,
  `free_period_shared_bike_start` = ?,
  `free_period_shared_bike_end` = ?,
  `free_period_shared_car` = ?,
  `free_period_shared_car_start` = ?,
  `free_period_shared_car_end` = ?,
  `free_period_bus` = ?,
  `free_period_bus_start` = ?,
  `free_period_bus_end` = ?,
  `free_period_tram` = ?,
  `free_period_tram_start` = ?,
  `free_period_tram_end` = ?,
  `free_period_metro` = ?,
  `free_period_metro_start` = ?,
  `free_period_metro_end` = ?,
  `free_period_train` = ?,
  `free_period_train_start` = ?,
  `free_period_train_end` = ?
WHERE
  `user_id` = ?");

    $stmt->bind_param("ississississississi", 
        $freePeriods[0], $freeFroms[0], $freeTos[0], 
        $freePeriods[1], $freeFroms[1], $freeTos[1], 
        $freePeriods[2], $freeFroms[2], $freeTos[2], 
        $freePeriods[3], $freeFroms[3], $freeTos[3], 
        $freePeriods[4], $freeFroms[4], $freeTos[4], 
        $freePeriods[5], $freeFroms[5], $freeTos[5], 
        $_SESSION['userid']);
    $status = $stmt->execute();

    if ($status === false) {
        $return .= "Failed to update transportations.\n";
    }

    if ($return === "") {
      $return = "Settings updated\n";
    }

    echo $return;
?>