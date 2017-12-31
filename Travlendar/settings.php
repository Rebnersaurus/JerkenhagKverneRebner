<?php include './dbc/databaseconnection.php';
    include 'secureinput.php';

    session_start();

    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
    }


    $sql = ("SELECT * FROM `users` WHERE `id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $address = $row['home'];

    $sql = ("SELECT * FROM `relevances` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $dryness = $row['dryness'];
    $price = $row['price'];
    $carbon = $row['carbon'];
    $speed = $row['speed'];

    // DISTANCES
    $sql = ("SELECT * FROM `max_distances` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $maxWalk = $row['max_walk'];
    $maxBike = $row['max_bike'];

    $sql = ("SELECT * FROM `means_of_transports` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    function checkAvailable($value){
        if($value){
            return " checked";
        } else {
            return "";
        }
    }

    $availableWalk = checkAvailable($row['available_walk']);
    $availableBike = checkAvailable($row['available_bike']);
    $availableSharedBike = checkAvailable($row['available_shared_bike']);
    $availableCar = checkAvailable($row['available_car']);
    $availableSharedCar = checkAvailable($row['available_shared_car']);
    $availableBus = checkAvailable($row['available_bus']);
    $availableTram = checkAvailable($row['available_tram']);
    $availableMetro = checkAvailable($row['available_metro']);
    $availableTrain = checkAvailable($row['available_train']);
    $availableTaxi = checkAvailable($row['available_taxi']);

    $walkFrom = $row['earliest_walk'];
    $bikeFrom = $row['earliest_bike'];
    $sharedBikeFrom = $row['earliest_shared_bike'];
    $carFrom = $row['earliest_car'];
    $sharedCarFrom = $row['earliest_shared_car'];
    $busFrom = $row['earliest_bus'];
    $tramFrom = $row['earliest_tram'];
    $metroFrom = $row['earliest_metro'];
    $trainFrom = $row['earliest_train'];
    $taxiFrom = $row['earliest_taxi'];

    $walkTo = $row['latest_walk'];
    $bikeTo = $row['latest_bike'];
    $sharedBikeTo = $row['latest_shared_bike'];
    $carTo = $row['latest_car'];
    $sharedCarTo = $row['latest_shared_car'];
    $busTo = $row['latest_bus'];
    $tramTo = $row['latest_tram'];
    $metroTo = $row['latest_metro'];
    $trainTo = $row['latest_train'];
    $taxiTo = $row['latest_taxi'];

    $sharedBikeTo = $row['latest_shared_bike'];

    $sql = ("SELECT * FROM `public_transports` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $availableFreeSharedBike = checkAvailable($row['free_period_shared_bike']);
    $availableFreeSharedCar = checkAvailable($row['free_period_shared_car']);
    $availableFreeBus = checkAvailable($row['free_period_bus']);
    $availableFreeTram = checkAvailable($row['free_period_tram']);
    $availableFreeMetro = checkAvailable($row['free_period_metro']);
    $availableFreeTrain = checkAvailable($row['free_period_train']);

    $freeSharedBikeFrom = $row['free_period_shared_bike_start'];
    $freeSharedCarFrom = $row['free_period_shared_car_start'];
    $freeBusFrom = $row['free_period_bus_start'];
    $freeTramFrom = $row['free_period_tram_start'];
    $freeMetroFrom = $row['free_period_metro_start'];
    $freeTrainFrom = $row['free_period_train_start'];

    $freeSharedBikeTo = $row['free_period_shared_bike_end'];
    $freeSharedCarTo = $row['free_period_shared_car_end'];
    $freeBusTo = $row['free_period_bus_end'];
    $freeTramTo = $row['free_period_tram_end'];
    $freeMetroTo = $row['free_period_metro_end'];
    $freeTrainTo = $row['free_period_train_end'];

    $breaks = 'No breaks.';

    $sql = ("SELECT * FROM `flexible_breaks` WHERE `user_id` = " . $_SESSION['userid']);
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        if($breaks === 'No breaks.'){
            $breaks = '';
        }

        $breaks .= createBreak($row['string'], $row['start'], $row['end'], $row['duration']);
    }

    function createBreak($name, $start, $end, $duration){
    return '
                    <section class="break">
                        <p>Name:</p>
                        <input type="text" name="breakName" value="' . $name . '">
                        <p>From:</p>
                        <input type="time" name="startTime" value="' . $start . '">
                        <p>To:</p>
                        <input type="time" name="endTime" value="' . $end . '">
                        <p>Duration:</p>
                        <input type="number" name="duration" value="' . $duration . '">
                        <img src="icons/save.svg" onclick="saveBreak(this)">
                        <img src="icons/garbage.svg" onclick="removeBreak(this)">
                    </section>';
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>Travlendar+ - Settings</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">
            var dryness = "<?=$dryness?>";
            var money = "<?=$price?>";
            var eco = "<?=$carbon?>";
            var speed = "<?=$speed?>";

            function setRelevance(cat, rel){
                var buttons = document.getElementsByClassName(cat);

                for (var i = 0; i < 4; i++) {
                    buttons[i].style.backgroundColor = "grey";
                }

                if (rel == "none") {
                    buttons[0].style.backgroundColor = "lime";
                } else if (rel == "low") {
                    buttons[1].style.backgroundColor = "lime";
                } else if (rel == "medium") {
                    buttons[2].style.backgroundColor = "lime";
                } else if (rel == "high") {
                    buttons[3].style.backgroundColor = "lime";
                }
            }

            function changeDryness(rel){
                dryness = rel;
                setRelevance('dryness', dryness);
            }

            function changeMoney(rel){
                money = rel;
                setRelevance('money', money);
            }

            function changeEco(rel){
                eco = rel;
                setRelevance('eco', eco);
            }

            function changeSpeed(rel){
                speed = rel;
                setRelevance('speed', speed);
            }

            window.onload = function(){
                setRelevance('dryness', dryness);
                setRelevance('money', money);
                setRelevance('eco', eco);
                setRelevance('speed', speed);
            }

            function addBreak(){
                document.getElementById("breaks").innerHTML += '<section class="break"><p>Name:</p><input type="text" name="breakName"><p>From:</p><input type="time" name="startTime"><p>To:</p><input type="time" name="endTime"><p>Duration:</p><input type="number" name="duration"><img src="icons/save.svg" onclick="saveBreak(this)"><img src="icons/garbage.svg" onclick="removeBreak(this)"></section>';
            }

            function save(){
                document.getElementById("saveButton").className = "spin";

                var distanceValues = [];
                var startTimeValues = [];
                var stopTimeValues = [];
                var availableMoTValues = [];
                var relevanceValues = [];
                var freePeriodsValues = [];
                var freeFromValues = [];
                var freeToValues = [];

                relevanceValues[0] = dryness;
                relevanceValues[1] = money;
                relevanceValues[2] = eco;
                relevanceValues[3] = speed;

                var address = document.getElementById('address').value;
                
                var distances = document.getElementsByClassName('distanceValue');

                for (var i = 0; i < distances.length; i++) {
                    distanceValues[i] = distances[i].value;
                }

                var availableMoTs = document.getElementsByClassName('availableMoT');

                for (var i = 0; i < availableMoTs.length; i++) {
                    if (availableMoTs[i].checked) {
                        availableMoTValues[i] = 1;
                    } else {
                        availableMoTValues[i] = 0;
                    }
                }

                var startTimes = document.getElementsByClassName('timeFrom');

                for (var i = 0; i < startTimes.length; i++) {
                    startTimeValues[i] = startTimes[i].value;
                }

                var stopTimes = document.getElementsByClassName('timeTo');

                for (var i = 0; i < stopTimes.length; i++) {
                    stopTimeValues[i] = stopTimes[i].value;
                }

                var freePeriods = document.getElementsByClassName('freePeriod');

                for (var i = 0; i < freePeriods.length; i++) {
                    if (freePeriods[i].checked) {
                        freePeriodsValues[i] = 1;
                    } else {
                        freePeriodsValues[i] = 0;
                    }
                }

                var freeFrom = document.getElementsByClassName('freePeriodFrom');

                for (var i = 0; i < freeFrom.length; i++) {
                    freeFromValues[i] = freeFrom[i].value;
                }

                var freeTo = document.getElementsByClassName('freePeriodTo');

                for (var i = 0; i < freeTo.length; i++) {
                    freeToValues[i] = freeTo[i].value;
                }

                $.ajax({
                    type: "POST",
                    data: {address:address,
                        relevanceValues:relevanceValues,
                        distanceValues:distanceValues, 
                        startTimeValues:startTimeValues,
                        stopTimeValues:stopTimeValues,
                        availableMoTValues:availableMoTValues,
                        freePeriodsValues:freePeriodsValues,
                        freeFromValues:freeFromValues,
                        freeToValues:freeToValues},
                    url: 'updateSettings.php',
                    success: function(msg){
                        console.log(msg);
                        document.getElementById("saveButton").className = "";
                    }
                });
            }
        </script>
    </head>
    <body>
        <header>
            <h1>Settings</h1>
            <div id="icmenu">
                <img id="saveButton" src="icons/save.svg" onclick="save()">
                <a href="./index.php"><img src="icons/return.svg"></a>
            </div>
        </header>
        <main>
            <section id="settings">
                <h1>Home address</h1>
                <hr>
                <section>
                    <p>Home Address</p>
                    <input id="address" type="text" name="address" value="<?=$address?>">
                </section>
                <h1>Preferences</h1>
                <hr>
                <section id="preferences">
                    <section class="preference">
                        <p>Dryness</p>
                        <button class="dryness relNone" onclick="changeDryness('none')">None</button>
                        <button class="dryness relLow" onclick="changeDryness('low')">Low</button>
                        <button class="dryness relMedium" onclick="changeDryness('medium')">Medium</button>
                        <button class="dryness relHigh" onclick="changeDryness('high')">High</button>
                    </section>
                    <section class="preference">
                        <p>Money</p>
                        <button class="money relNone" onclick="changeMoney('none')">None</button>
                        <button class="money relLow" onclick="changeMoney('low')">Low</button>
                        <button class="money relMedium" onclick="changeMoney('medium')">Medium</button>
                        <button class="money relHigh" onclick="changeMoney('high')">High</button>
                    </section>
                    <section class="preference">
                        <p>Eco</p>
                        <button class="eco relNone" onclick="changeEco('none')">None</button>
                        <button class="eco relLow" onclick="changeEco('low')">Low</button>
                        <button class="eco relMedium" onclick="changeEco('medium')">Medium</button>
                        <button class="eco relHigh" onclick="changeEco('high')">High</button>
                    </section>
                    <section class="preference">
                        <p>Speed</p>
                        <button class="speed relNone" onclick="changeSpeed('none')">None</button>
                        <button class="speed relLow" onclick="changeSpeed('low')">Low</button>
                        <button class="speed relMedium" onclick="changeSpeed('medium')">Medium</button>
                        <button class="speed relHigh" onclick="changeSpeed('high')">High</button>
                    </section>
                </section>
                <h1>Max distances</h1>
                <hr>
                <section id="distances">
                    <section class="distance">
                        <p>Max walking distance: </p>
                        <input class="distanceValue" type="number" step="1" min="0" value="<?=$maxWalk?>" name="maxWalk">
                    </section>
                    <section class="distance">
                        <p>Max biking distance: </p>
                        <input class="distanceValue" type="number" step="1" min="0" value="<?=$maxBike?>" name="maxBike">
                    </section>
                </section>
                <h1>Means of Transport</h1>
                <hr>
                <section id="mot">
                    <section class="mot" id="motWalk">
                        <p class="motName">Walk</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableWalk"<?=$availableWalk?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="walkFrom" value="<?=$walkFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="walkTo" value="<?=$walkTo?>">
                    </section>
                    <section class="mot" id="motBike">
                        <p class="motName">Bike</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableBike"<?=$availableBike?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="bikeFrom" value="<?=$bikeFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="bikeTo" value="<?=$bikeTo?>">
                    </section>
                    <section class="mot" id="motSharedBike">
                        <p class="motName">Shared Bike</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableSharedBike"<?=$availableSharedBike?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="sharedBikeFrom" value="<?=$sharedBikeFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="sharedBikeTo" value="<?=$sharedBikeTo?>">
                        <br>
                        <section class="publicmot">
                            <p>Free period available </p>
                            <div class="available">
                                <input class="freePeriod" type="checkbox" name="freeSharedBike"<?=$availableFreeSharedBike?>>
                            </div>
                            <p>From: </p>
                            <input class="freePeriodFrom" type="date" name="freeSharedBikeFrom" value="<?=$freeSharedBikeFrom?>">
                            <p>To: </p>
                            <input class="freePeriodTo" type="date" name="freeSharedBikeTo" value="<?=$freeSharedBikeTo?>">
                        </section>
                    </section>
                    <section class="mot" id="motCar">
                        <p class="motName">Car</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableCar"<?=$availableCar?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="carFrom" value="<?=$carFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="carTo" value="<?=$carTo?>">
                    </section>
                    <section class="mot" id="motSharedCar">
                        <p class="motName">Shared Car</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableSharedCar"<?=$availableSharedCar?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="sharedCarFrom" value="<?=$sharedCarFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="sharedCarTo" value="<?=$sharedCarTo?>">
                        <br>
                        <section class="publicmot">
                            <p>Free period available </p>
                            <div class="available">
                                <input class="freePeriod" type="checkbox" name="freeSharedCar"<?=$availableFreeSharedCar?>>
                            </div>
                            <p>From: </p>
                            <input class="freePeriodFrom" type="date" name="freeSharedCarFrom" value="<?=$freeSharedCarFrom?>">
                            <p>To: </p>
                            <input class="freePeriodTo" type="date" name="freeSharedCarTo" value="<?=$freeSharedCarTo?>">
                        </section>
                    </section>
                    <section class="mot" id="motBus">
                        <p class="motName">Bus</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableBus"<?=$availableBus?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="busFrom" value="<?=$busFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="busTo" value="<?=$busTo?>">
                        <br>
                        <section class="publicmot">
                            <p>Free period available </p>
                            <div class="available">
                                <input class="freePeriod" type="checkbox" name="freeBus"<?=$availableFreeBus?>>
                            </div>
                            <p>From: </p>
                            <input class="freePeriodFrom" type="date" name="freeBusFrom" value="<?=$freeBusFrom?>">
                            <p>To: </p>
                            <input class="freePeriodTo" type="date" name="freeBusTo" value="<?=$freeBusTo?>">
                        </section>
                    </section>
                    <section class="mot" id="motTram">
                        <p class="motName">Tram</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableTram"<?=$availableTram?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="tramFrom" value="<?=$tramFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="tramTo" value="<?=$tramTo?>">
                        <br>
                        <section class="publicmot">
                            <p>Free period available </p>
                            <div class="available">
                                <input class="freePeriod" type="checkbox" name="freeTram"<?=$availableFreeTram?>>
                            </div>
                            <p>From: </p>
                            <input class="freePeriodFrom" type="date" name="freeTramFrom" value="<?=$freeTramFrom?>">
                            <p>To: </p>
                            <input class="freePeriodTo" type="date" name="freeTramTo" value="<?=$freeTramTo?>">
                        </section>
                    </section>
                    <section class="mot" id="motMetro">
                        <p class="motName">Metro</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableMetro"<?=$availableMetro?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="metroFrom" value="<?=$metroFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="metroTo" value="<?=$metroTo?>">
                        <br>
                        <section class="publicmot">
                            <p>Free period available </p>
                            <div class="available">
                                <input class="freePeriod" type="checkbox" name="freeMetro"<?=$availableFreeMetro?>>
                            </div>
                            <p>From: </p>
                            <input class="freePeriodFrom" type="date" name="freeMetroFrom" value="<?=$freeMetroFrom?>">
                            <p>To: </p>
                            <input class="freePeriodTo" type="date" name="freeMetroTo" value="<?=$freeMetroTo?>">
                        </section>
                    </section>
                    <section class="mot" id="motTrain">
                        <p class="motName">Train</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableTrain"<?=$availableTrain?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="trainFrom" value="<?=$trainFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="trainTo" value="<?=$trainTo?>">
                        <br>
                        <section class="publicmot">
                            <p>Free period available </p>
                            <div class="available">
                                <input class="freePeriod" type="checkbox" name="freeTrain"<?=$availableFreeTrain?>>
                            </div>
                            <p>From: </p>
                            <input class="freePeriodFrom" type="date" name="freeTrainFrom" value="<?=$freeTrainFrom?>">
                            <p>To: </p>
                            <input class="freePeriodTo" type="date" name="freeTrainTo" value="<?=$freeTrainTo?>">
                        </section>
                    </section>
                    <section class="mot" id="motTaxi">
                        <p class="motName">Taxi</p>
                        <br>
                        <div class="available">
                            <p>Available</p>
                            <input class="availableMoT" type="checkbox" name="availableTaxi"<?=$availableTaxi?>>
                        </div>
                        <p>From: </p>
                        <input class="timeFrom" type="time" name="taxiFrom" value="<?=$taxiFrom?>">
                        <p>To: </p>
                        <input class="timeTo" type="time" name="taxiTo" value="<?=$taxiTo?>">
                        <br>
                    </section>
                </section>
                <h1>Flexible Breaks</h1>
                <hr>
                <button onclick="addBreak()">Add Break</button>
                <section id="breaks">
                    <?=$breaks?>
                </section>
            </section>
        </main>
    </body>
</html>
