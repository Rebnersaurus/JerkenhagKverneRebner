<?php include './dbc/databaseconnection.php';
    include 'secureinput.php';

    session_start();

    $username = '';

    $errormessage = '';

    // IF THE USER IS LOGGED IN, LOGUT USER FIRST
    if (isset($_SESSION['userid'])) {
        header("Location: index.php");
    }

    // IF THE USER TRIES TO LOGIN
    if (isset($_POST['login'])) {
        $username = secureInput($_POST['username']);
        $password = secureInput($_POST['password']);

        $password = md5($password);

        $stmt = $conn->prepare("SELECT `id` FROM `users` WHERE `username` = ? AND `password` = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();

        if ($id != '') {
            $_SESSION["userid"] = $id;
            $_SESSION["username"] = $username;
            header("Location: index.php");
        } else {
            $errormessage = '<p id="errormessage">Invalid username or password.</p>';
        }

        $conn->close();
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>Travlendar+</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script type="text/javascript">
        </script>
    </head>
    <body>
        <main>
            <section class="miniBox" id="login">
                <form action="login.php" method="post">
                    <?=$errormessage?>
                    <p>Username:</p>
                    <input id="username" type="text" name="username" value="<?=$username?>" maxlength="20" autofocus> 
                    <p>Password:</p>
                    <input id="password" type="password" name="password" maxlength="20">
                    <button type="submit" name="login">Login</button>
                    <a href="forgot.php">Forgot password?</a>
                    <br>
                    New here? <a href="new.php">Create a user.</a>
                </form>
            </section>
        </main>
    </body>
</html>
