<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once "ControlePagamento.php";
$controller = new ControlePagamento();
$controller->init();
?>