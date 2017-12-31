<?php include './dbc/databaseconnection.php';

    session_start();

    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
    }

    $stmt = $conn->prepare("DELETE
FROM
  `events`
WHERE
  `user_id` = ?
AND
  `event_id` = ?");
    $stmt->bind_param("ii", $_SESSION['userid'], $_GET['event']);

    if ($stmt->execute()) {
        header("Location: index.php");
    }
    
    $stmt->close();
    $conn->close();
?>