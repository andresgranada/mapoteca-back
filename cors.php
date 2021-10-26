<?php
$dominioPermitido = "http://localhost:3000";
header("Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('content-type: application/json; charset=utf-8');