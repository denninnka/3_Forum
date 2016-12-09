<?php
session_start();
if (!isset($_SESSION['isLogged'])) {
    $_SESSION['isLogged'] = false;
}
require './includes/menuselect.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title><?=$pageTitle;?></title>
</head>
<body>
