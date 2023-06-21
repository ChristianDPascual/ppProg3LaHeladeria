<?php

require_once("Venta.php");
require_once("Helados.php");
require_once("Devolucion.php");
include("funciones.php");

$path = "C:\Users\SchryD\Desktop\ImagenesDeHelados/2023/";
$ruta = "venta.json";
$ruta2 = "cupones.json";
$ruta3 = "devoluciones.json";
$pedidoNro = $_POST["pedidoNro"];
$motivo = $_POST["motivo"];
$archivo= $_FILES['foto'];

$listado = Cliente :: cargarVentasJSON($ruta);

if(isset($listado) && validarNumero($pedidoNro))
{

        if(filesize($ruta2)>0)
        {
            $listaCupones = Devolucion :: cargarCuponesJSON($ruta2);//cargar listadoJson cupones
            $id=Devolucion :: buscarPedido($listado,$pedidoNro);

            if($id>0)
            {
                if(validarCadena($motivo) && $id>0 && validarArchivos($archivo))
                {
                    $cupon = new Devolucion($pedidoNro,$motivo,$id,null,null,null);
                    Devolucion :: moverImagenDevolucion($archivo,$cupon);
                    array_push($listaCupones,$cupon);
                    Devolucion :: guardarCupones($listaCupones,$ruta2);
                    Devolucion :: guardarDevoluciones($listaCupones,$ruta3);
                }
            }
            else
            {
                if($id == -1)
                {
                    echo "Ya se realizo una devolucion sobre ese pedido\n";
                }
                else
                {
                    echo "No se encontraron coincidencias\n";
                }
            }
        }
        else
        {
            $id=Devolucion :: buscarPedido($listado,$pedidoNro);
            if($id>0)
            {
                if(validarCadena($motivo) && $id>0 && validarArchivos($archivo))
                {
                    $cupon = new Devolucion($pedidoNro,$motivo,$id,null,null,null);
                    Devolucion :: moverImagenDevolucion($archivo,$cupon);
                    $nuevaLista = array();
                    $nuevaLista[] = $cupon;
                    Devolucion :: guardarCupones($nuevaLista,$ruta2);
                    Devolucion :: guardarDevoluciones($nuevaLista,$ruta3);
                }
            }
            else
            {
                if($id == -1)
                {
                    echo "Ya se realizo una devolucion sobre ese pedido\n";
                }
                else
                {
                    echo "No se encontraron coincidencias\n";
                }
            }
            
        }
}
else
{
    echo "Ocurrio un error durante el proceso\n";
}

?>