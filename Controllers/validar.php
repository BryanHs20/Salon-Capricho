<?php 

$usuario=$_POST['email'];
$contraseña=$_POST['password'];
session_start();
$_SESSION['email']=$usuario;

$conexion= mysqli_connect("localhost","u196012216_RyAdmin","Ry#20072002#","u196012216_capricho");

$consulta="SELECT * FROM login WHERE usuario='$usuario' AND contraseña='$contraseña'";
$resultado= mysqli_query($conexion, $consulta);

$fila= mysqli_num_rows($resultado);

if ($fila) {
    header("location:../../../Views/");
} 
else {
    include("../Views/login.php");
    echo "Error en la operación";
}

mysqli_free_result($resultado);
mysqli_close($conexion);