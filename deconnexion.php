<?php
session_start();
$_SESSION = array();
session_destroy();
echo "Vous êtes deconnecté";
header("Location: index.php");

?>