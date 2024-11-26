<?php
session_start();

$session_i = $_SESSION['cliente'];

if($session_i == null || $session_i == " "){
    include("../bienvenida/index.php");
}else{
    include("../principal/menu.php");
}

?>