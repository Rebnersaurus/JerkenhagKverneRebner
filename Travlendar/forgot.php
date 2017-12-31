<?php include './dbc/databaseconnection.php';
    include 'secureinput.php';

    session_start();

    if (isset($_SESSION['userid'])) {
        header("Location: logout.php");
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>Travlendar+</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <main>
            <section class="miniBox" id="resetPW">
                <form>
                    <p>E-mail:</p>
                    <input type="text" name="email">
                    <button type="submit">Reset</button>
                </form>
            </section>
        </main>
    </body>
</html>
