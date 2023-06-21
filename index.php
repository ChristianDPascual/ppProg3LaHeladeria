<?php
/*
pt.) index.php:Recibe todas las peticiones que realiza el postman, y administra a qué archivo se
 debe incluir.
 */
switch($_SERVER['REQUEST_METHOD'])
{
    case "GET":
        switch ($_GET["accion"])
        {
            case 'consulta':
                require_once('ConsultasVentas.php');
            break;
            
            case 'consultaDevoluciones':
                require_once('ConsultasDevoluciones.php');
            break;
        }
        break;
    case "POST":
        switch($_POST["accion"])
        {
            case 'alta':
                require_once('HeladeriaAlta.php');
            break;

            case 'consulta':
                require_once('HeladoConsultar.php');
            break;

            case 'venta':
                require_once('AltaVenta.php');
            break;

            case 'devolucion':
                require_once('DevolverHelado.php');
                break;
        }
        break;
    case "PUT":
         require_once('ModificarVenta.php');
        break;
    case "DELETE":
          require_once('borrarVenta.php');
        break;

        
}


?>