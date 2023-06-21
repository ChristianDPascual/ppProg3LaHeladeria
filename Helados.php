<?php

class Helado
{
    public $sabor;
    public $precio;
    public $tipo;
    public $stock;
    public $vaso;
    public $id;


    public function __construct($sabor,$precio,$tipo,$stock,$vaso,$id)
    {
        $ruta = 'idHelados.csv';
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->stock= $stock;
        $this->vaso= $vaso;
        if(isset($id))
        {
            $this->id = $id;
        }
        else
        {
            $this->id = controlID($ruta);
        }
    }

    public static function compararHelados($listado ,$h)
    {
        if(count($listado)>0 && isset($h))
        {
            $contador = 0;
            foreach($listado as $helado)
            {
                if($helado->sabor == $h->sabor && $helado->tipo == $h->tipo)
                {
                    $helado->stock = $helado->stock + $h->stock;
                    $helado->precio = $h->precio;
                    $contador ++;
                    break;
                }
            }

            if($contador == 0)
            {
                array_push($listado,$h);
                echo "el helado se agrego al listado\n";
                return $listado;
                
            }
            else
            {
                echo "el listado de modificio exitosamente\n";
                return $listado;
            }
        }
        else
        {
            if(count($listado)==0 && isset($h))
            {
                array_push($listado,$h);
                echo "el helado se agrego al listado\n";
                return $listado;
            }
            else
            {
                echo "ocurrio un error al comparar\n";
            }
        }
    }

    public static function cargarJson($path)
    {
        if(filesize($path))
        {
            $obtenerDatosArchJson = file_get_contents($path);

            $lista = json_decode($obtenerDatosArchJson, true);
            $listaRetorno = array();

            foreach($lista as $h)
            {
                ////$sabor,$precio,$tipo,$stock,$vaso,$id
                $aux = new Helado($h["sabor"],$h["precio"],$h["tipo"],$h["stock"],$h["vaso"],$h["id"]);
                $listaRetorno[] = $aux;
            }

            return $listaRetorno;
        }
        else
        {
            echo "se creo un nuevo listado\n";
        }   
    }

    public static function moverImagenPost($archivo,$h)
    {

        if(isset($archivo))
        {   

            $destino = "C:\Users\SchryD\Desktop\ImagenesDeHelados/2023/".$archivo["name"];
            $nuevoNombre = "$h->sabor"."$h->tipo".".jpg";
            
            if(move_uploaded_file($archivo["tmp_name"], $destino))
            {
                echo "\nSe movio exitosamente la foto\n";
                if(rename($destino, "C:\Users\SchryD\Desktop\ImagenesDeHelados/2023/$nuevoNombre"))
                {
                    echo "\nSe cambio el nombre de la foto\n";
                }
            }
            else
            {
                echo "no se movio la imagen\n";
            }
            
        }
        else
        {
            echo "Ocurrio un error con la imagen";
        }
    }

    public static function guardarJson($listado,$path)
    {
        if(isset($path))
        {
            $archivo = fopen($path,"w");
            fwrite($archivo,json_encode($listado));
            fclose($archivo);
        }
        else
        {
            echo "ocurrio un error con guardar el listado en json\n";
        }
    }

    public static function consultarListadoJson($path, $sabor, $tipo)
    {
        if(isset($path) && isset($sabor) && isset($tipo))
        {
            $obtenerDatosArchJson = file_get_contents($path);

            $listado = json_decode($obtenerDatosArchJson, true);

            $contador = 0;

            foreach($listado as $p)
            {
                if($p["sabor"] == $sabor && $p["tipo"] == $tipo)
                {
                    $contador++;
                    break;
                }
            }

            if($contador == 0)
            {
                echo "No se encontraron helados que coincidan\n";
            }
            else
            {
                echo "Si hay\n";
            }
        }
        else
        {
            echo "ocurrio un error al traer el listado desde json\n";
        }
    }

    public static function consultarStock($sabor,$tipo,$stock,$vaso)
    {
        $path = "heladeria.json";
        if(isset($sabor) && isset($stock) && isset($tipo) && file_exists($path))
        {
            $listado = Helado :: cargarJson($path);

            if(count($listado)>0)
            {
                $contador = 0;
                foreach($listado as $helado)
                {
                    if($helado->sabor == $sabor && $helado->tipo == $tipo && $helado->stock >= $stock && $helado->vaso == $vaso)
                    {
                        $contador ++;
                        break;
                    }
                }
    
                if($contador == 0)
                {
                    return 0;
                }
                else
                {
                    return 1;
                }
            }
            else
            {
                echo "no hay stock de helados\n";
                return 0;
            }
        
        }
        else
        {
            echo "ocurrio un error al tratar de realizar venta\n"; 
            return 0;
        }


    }



}


?>