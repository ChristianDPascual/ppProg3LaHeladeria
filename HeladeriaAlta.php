<?php
require_once("Helados.php");
include("funciones.php");

$ruta = "heladeria.json";
$sabor = $_POST["sabor"];
$tipo = $_POST["tipo"];
$precio= $_POST["precio"];
$stock= $_POST["stock"];
$vaso= $_POST["vaso"];
$archivo = $_FILES["foto"];

if(validarCadena($sabor) && validarCondicionTipo($tipo) && validarPrecio($precio) && validarNumero($stock)
  && validarArchivos($archivo) && validarCondicionVaso($vaso))
{
    //$sabor,$precio,$tipo,$stock,$vaso,$id
    $helado = new Helado($sabor,$precio,$tipo,$stock,$vaso,null);
    $listado = Helado :: cargarJson($ruta);

    if(isset($listado))
    {
        $listado = Helado :: compararHelados($listado,$helado);
        Helado :: moverImagenPost($archivo,$helado);
        Helado :: guardarJson($listado,$ruta);
    }
    else
    {
        $nuevaLista = array();
        $nuevaLista[] = $helado;
        Helado :: moverImagenPost($archivo,$helado);
        Helado :: guardarJson($nuevaLista,$ruta);

    }
}
else
{
    echo "no se pudo crear el helado\n";
}

?>