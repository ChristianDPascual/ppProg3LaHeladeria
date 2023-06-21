<?php

require_once("Helados.php");
include("funciones.php");

$path = "heladeria.json";
$sabor = $_POST["sabor"];
$tipo = $_POST["tipo"];

Helado :: consultarListadoJson($path, $sabor, $tipo);

?>