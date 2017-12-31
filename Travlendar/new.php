<?php include './dbc/databaseconnection.php';
    include 'secureinput.php';

    session_start();

    $username = '';
    $email = '';

    $errormessage = '';

    // IF THE USER IS LOGGED IN, LOGUT USER FIRST
    if (isset($_SESSION['userid'])) {
        header("Location: logout.php");
    }

    if (isset($_POST['create'])) {
        $username = secureInput($_POST['username']);
        $password = secureInput($_POST['password']);
        $email = secureInput($_POST['email']);

        $password = md5($password);

        $stmt = $conn->prepare("INSERT INTO `users`(`username`, `password`, `mail`) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            header("Location: login.php");
        } else {
            $errormessage = '<p id="errormessage">Invalid credentials.</p>';
        }

        $stmt->close();
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
    </head>
    <body>
        <main>
            <section class="miniBox" id="newUser">
                <form action="new.php" method="post">
                    <?=$errormessage?>
                    <p>Username:</p>
                    <input type="text" name="username" value="<?=$username?>" maxlength="20" autofocus>
                    <p>Password:</p>
                    <input type="password" name="password"  maxlength="20">
                    <p>E-mail:</p>
                    <input type="email" name="email" value="<?=$email?>" maxlength="50">
                    <button type="submit" name="create">Create</button>
                </form>
            </section>
        </main>
    </body>
</html>
