<?php include './dbc/databaseconnection.php';
    include 'secureinput.php';

    session_start();

    if (!isset($_SESSION['userid'])) {
        header("Location: ./login.php");
    }

    $id = secureInput($_GET['event']);

    $sql = ("SELECT * FROM `events` WHERE `user_id` = '" . $_SESSION['userid'] . "'  AND `event_id` = '" . $id . "'");
    $result = $conn->query($sql);
    $previousDate = '';
    if ($row = $result->fetch_assoc()) {
        $name = $row['title'];
        $date = $row['date'];
        $start = $row['start'];
        $end = $row['end'];
        $to = $row['endadress'];
        $from = $row['startadress'];
        $passengers = $row['passengers'];
    } else {
        header("Location: ./index.php");        
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>Travlendar+</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTvxwzPka81IHjEC1fobm-LfQIXIvftuo&libraries=places&callback=initAutocomplete"
            async defer>
        </script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
            class route {
                print(){
                    var string = '<section class="route"><p>Depart: ' + this.departure + '</p><p>Arrive: ' + this.arrival + '</p><p>Distance: ' + this.distance + ' km</p><p>Walking: ' + this.walkingDistance + ' km</p></section>';
                }
            }
            var walkRoute = new route(), bikeRoute = new route(), driveRoute = new route(), transitRoute = new route();

            /*var toAddress = '';

            var placeSearch, autocomplete;
            var componentForm = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name'
            };

            function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
                autocomplete = new google.maps.places.Autocomplete(
                    (document.getElementById('autocomplete')),
                {types: ['geocode']});

                // When the user selects an address from the dropdown, populate the address
                // fields in the form.
                autocomplete.addListener('place_changed', fillInAddress);
            }
            
            function fillInAddress() {
                // Get the place details from the autocomplete object.
                var place = autocomplete.getPlace();

                for (var component in componentForm) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }

                // Get each component of the address from the place details
                // and fill the corresponding field on the form.
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];x
                        document.getElementById(addressType).value = val;
                    }
                }
            }

            // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.

            function geolocate() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var geolocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        var circle = new google.maps.Circle({
                            center: geolocation,
                            radius: position.coords.accuracy
                        });
                        autocomplete.setBounds(circle.getBounds());
                    });
                }
            }*/

            function generateRoutes(){
                document.getElementById("generate").className = "spin";

                var date = document.getElementById("eventDate").value;
                var time = document.getElementById("startTime").value;
                var epoch = new Date(date.substring(0, 4), date.substring(5, 7), date.substring(8, 10), time.substring(0,2), time.substring(3,5)).getTime() / 1000;

                var from = document.getElementById("startAddress").value;
                var to = document.getElementById("eventAddress").value;
                var time = epoch;

                getRoute("walking", "", from, to, time, "wr");
                getRoute("bicycling", "", from, to, time, "br");
                getRoute("driving", "", from, to, time, "dr");
                getRoute("transit", "", from, to, time, "tr");

                document.getElementById("generate").className = "";
            }

            function changeRoutesText(){
                var nr = document.getElementById("noroutes");
                var wr = document.getElementById("wr").innerHTML;
                var br = document.getElementById("br").innerHTML;
                var dr = document.getElementById("dr").innerHTML;
                var tr = document.getElementById("tr").innerHTML;

                if(wr == "" && br == "" && dr == "" && tr == ""){
                    nr.style.display = "block";
                } else {
                    nr.style.display = "none";
                }                
            }

            function getRoute($mode, $transit, $from, $to, $time, $div){
                $.ajax({
                    type: "POST",
                    data: {mode:$mode,
                    transit:$transit,
                    from:$from,
                    to:$to,
                    arrival:$time},
                    url: 'getTransportations.php',
                    success: function(msg){
                        document.getElementById($div).innerHTML = msg;
                        changeRoutesText();
                    }
                });
            }

            function save(){
                var title = document.getElementById('eventName').value;
                var date = document.getElementById('eventDate').value;
                var start = document.getElementById('startTime').value;
                var end = document.getElementById('endTime').value;
                var address = document.getElementById('eventAddress').value;
                var startAddress = document.getElementById('startAddress').value;
                var passengers = document.getElementById('passengers').value;

                var routes = document.getElementsByName('route');
                var rTimes = document.getElementsByClassName('rTime');
                var rEco = document.getElementsByClassName('rEco');
                var rCost = document.getElementsByClassName('rCost');
                var rWet = document.getElementsByClassName('rWet');
                var route = "";
                var transfer = "";
                var eco = "";
                var cost = "";
                var wet = "";

                for (var i = 0; i < routes.length; i++) {
                    if (routes[i].checked) {
                        route = routes[i].value;
                        transfer = rTimes[i].innerHTML;
                        eco = rEco[i].innerHTML;
                        cost = rCost[i].innerHTML;
                        wet = rWet[i].innerHTML;
                    }
                }

                var safe = 1;
                var alert = "";

                /*if (new Date(date) < new Date()) {
                    safe = 0;
                    alert += "Can not be past date.\n";
                }*/

                if (start >= end) {
                    safe = 0;
                    alert += "Must end after start.\n";
                }

                if (address === "") {
                    safe = 0;
                    alert += "Must choose a destination.\n"
                }

                if (startAddress === "") {
                    safe = 0;
                    alert += "Must choose a departure.\n"
                }

                if (route === "") {
                    safe = 0;
                    alert += "Must choose a route.\n";
                }

                if (safe !== 0) { 
                    $.ajax({
                        type: "POST",
                        data: {title:title,
                            date:date,
                            start:start,
                            end:end,
                            address:address,
                            startAddress:startAddress,
                            route:route,
                            transfer:transfer,
                            passengers:passengers,
                            eco:eco,
                            cost:cost,
                            wet:wet,
                            eventId:<?=$id?>},
                        url: 'updateEvent.php',
                        success: function(msg){
                            if(msg == "Event updated.\n"){
                                window.location.replace("./index.php");
                            } else {
                                window.alert(msg);
                            }
                        }
                    });   
                } else {
                    window.alert(alert);
                }
            }

            function cancel(){
                window.location.replace("./index.php");
            }
        </script>
    </head>
    <body>
        <header>
            <h1>Edit Event</h1>
            <div id="icmenu">
                <a href="./remove.php?event=<?=$id?>"><img src="icons/garbage.svg"></a>
                <a href="./index.php"><img src="icons/return.svg"></a>
            </div>
        </header>
        <main>
            <section id="eventForm">
                Name:<br>
                <input type="text" id="eventName" name="eventName" value="<?=$name?>"><br>
                Date:<br>
                <input type="date" id="eventDate" name="eventDate" value="<?=$date?>"><br><br>
                <b>Time</b><br>
                From:<br>
                <input type="time" id="startTime" name="startTime" value="<?=$start?>"><br>
                To:<br>
                <input type="time" id="endTime" name="endTime" value="<?=$end?>"><br>
                Where:<br>
                <input type="text" id="eventAddress" name="eventAddress" value="<?=$to?>"><br>
                <br>
                <!--<div id="locationField">
                <input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text"></input>
                </div>
                <table id="address">
                    <tr>
                        <td class="label">Street address</td>
                        <td class="slimField"><input class="field" id="street_number" disabled="true"></input></td>
                        <td class="wideField" colspan="2"><input class="field" id="route" disabled="true"></input></td>
                    </tr>
                    <tr>
                        <td class="label">City</td>
                        <td class="wideField" colspan="3"><input class="field" id="locality" disabled="true"></input></td>
                    </tr>
                    <tr>
                        <td class="label">State</td>
                        <td class="slimField"><input class="field" id="administrative_area_level_1" disabled="true"></input></td>
                        <td class="label">Zip code</td>
                        <td class="wideField"><input class="field" id="postal_code" disabled="true"></input></td>
                    </tr>
                    <tr>
                        <td class="label">Country</td>
                        <td class="wideField" colspan="3"><input class="field" id="country" disabled="true"></input></td>
                    </tr>
                </table>-->
                Coming from:<br>
                <input type="text" id="startAddress" name="startAddress" value="<?=$from?>"><br>
                Number of Passengers: 
                <select id="passengers" name="passengers" value="<?=$passengers?>">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select><br>
                <section id="side">
                    Routes
                    <span style="display:inline-block; width: 100px;"></span>
                    Generate
                    <img id="generate" src="icons/reload.svg" onClick="generateRoutes()">
                    <hr>
                    <section id="routes">
                        <div id="noroutes">No routes.</div>
                        <div id="wr"></div>
                        <div id="br"></div>
                        <div id="dr"></div>
                        <div id="tr"></div>
                    </section>
                    <hr>
                    <section id="buttons">
                        <button value="Submit" id="save" onclick="save()">Save</button>
                        <button value="Cancel" id="cancel" onclick="cancel()">Cancel</button>
                    </section>
                </section>
            </section>
        </main>
    </body>
</html>
