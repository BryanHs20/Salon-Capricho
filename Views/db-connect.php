<?php
$host     = 'localhost';
$username = 'u196012216_RyAdmin';
$password = 'Ry#20072002#';
$dbname   ='u196012216_capricho';

$conn = new mysqli($host, $username, $password, $dbname);
if(!$conn){
    die("No se pudo conectar a la base de datos.". $conn->error);
}