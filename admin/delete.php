<?php require '../include/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM stks WHERE id=$id");
header("Location: dashboard.php");
?>
