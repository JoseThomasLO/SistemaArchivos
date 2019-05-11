<?php
include_once("ConexionDB.php");
include_once("Archivo.php");

    class SistemaOperativo
    {

        public function listar($idArchivo) //Lista todos los hijos del archivo idArchivo
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                $sql = "";
                if(strcmp($idArchivo, "null") == 0) //Caso en el cual se lista C, que no tiene padre
                    //Crea la sentencia sql, para obtener los archivos hijos de la base de datos.
                    $sql = "SELECT id, nombre, tipo, padre FROM archivos WHERE padre is null";
                else
                    $sql = "SELECT id, nombre, tipo, padre FROM archivos WHERE padre = ".$idArchivo;
                $resultado = $conexion->query($sql);
                $archivos = array();
                while ($objeto = $resultado->fetch_object()) //Captura cada fila de la base de datos y la convierte a un objeto
                {
                    $archivo = new Archivo($objeto->id, $objeto->nombre, $objeto->tipo, $objeto->padre);
                    $archivos[] = $archivo;
                }
                $conexion->close(); //Cierra la conexion con la base de datos
                echo json_encode($archivos);
            }
            else
                echo json_encode("Error en la conexion");
        }

        public function volver($idArchivo) //Crea un nuevo archivo, cuyo padre sera el archivo idArchivo
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                $sql = "SELECT padre FROM archivos WHERE id = ".$idArchivo;
                $resultado = $conexion->query($sql);
                $objeto = $resultado->fetch_object(); //Captura cada fila de la base de datos y la convierte a un objeto
                $padre = $objeto->padre;
                $conexion->close(); //Cierra la conexion con la base de datos
                echo json_encode($padre);
            }
            else
                echo json_encode("Error en la conexion");
        }

        public function crear($idPadre, $nombre, $tipo) //Crea un nuevo archivo, cuyo padre sera el archivo idArchivo
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                if(strcmp($nombre, "") != 0)
                {
                    $sql = "INSERT INTO archivos VALUES (null, '".$nombre."', '".$tipo."', null, '".$idPadre."')";
                    if($conexion->query($sql))
                        echo json_encode(true);
                    else
                        echo json_encode(false);
                    $conexion->close(); //Cierra la conexion con la base de datos
                }
            }
            else
                echo json_encode("Error en la conexion");
        }

        public function eliminar($idArchivo)
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                    $sql = "DELETE FROM archivos WHERE id = ".$idArchivo;
                    if($conexion->query($sql))
                        echo json_encode(true);
                    else
                        echo json_encode(false);
                    $conexion->close(); //Cierra la conexion con la base de datos
                
            }
            else
                echo json_encode("Error en la conexion");
        }

        public function entregarCarpetas()
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                $sql = ""; 
                $sql = "SELECT id, nombre, tipo, padre FROM archivos WHERE tipo = 'Carpeta'";
                $resultado = $conexion->query($sql);
                $carpetas = array();
                while ($objeto = $resultado->fetch_object()) //Captura cada fila de la base de datos y la convierte a un objeto
                {
                    $carpeta = new Archivo($objeto->id, $objeto->nombre, $objeto->tipo, $objeto->padre);
                    $carpetas[] = $carpeta;
                }
                $conexion->close(); //Cierra la conexion con la base de datos
                echo json_encode($carpetas);
            }
            else
                echo json_encode("Error en la conexion");
        }

        public function mover($idArchivoAMover, $idNuevoPadre)
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                //Se realizara la validacion que impide que se mueva una carpeta a otra que esta dentro de si misma
                $sql1 = "SELECT padre FROM archivos WHERE id = '".$idNuevoPadre."'";
                $validacion = $conexion->query($sql1);
                $objeto = $validacion->fetch_object();
                $padreNuevoPadre = $objeto->padre;
                if($padreNuevoPadre == $idArchivoAMover)
                {
                    echo json_encode("No se puede mover una carpeta a una que este dentro de la misma");
                }
                else
                {
                    $sql = "UPDATE archivos SET padre = '".$idNuevoPadre."' WHERE id = '".$idArchivoAMover."'";
                    if($conexion->query($sql))
                        echo json_encode(true);
                    else
                        echo json_encode(false);
                }
                $conexion->close();
            }
            else
                echo json_encode("Error en la conexion");
        }

        public function renombrar($idArchivo, $nuevoNombre)
        {
            $host = ConexionDB::$host; //Accede a los atributos estaticos para iniciar la conexion a la DB
            $user = ConexionDB::$user;
            $database = ConexionDB::$database;
            $password = ConexionDB::$password;
            // Realiza la conexion con la base de datos
            $conexion = new mysqli($host, $user, $password, $database);
            if(!$conexion->connect_errno)
            {
                    $sql = "UPDATE archivos SET nombre = '".$nuevoNombre."' WHERE id = '".$idArchivo."'";
                    if($conexion->query($sql))
                        echo json_encode(true);
                    else
                        echo json_encode(false);
                    $conexion->close(); //Cierra la conexion con la base de datos
                
            }
            else
                echo json_encode("Error en la conexion");
        }
    }

    $sistemaOperativo = new SistemaOperativo();
    if ($_POST["operacion"] == 0)
        $sistemaOperativo->listar($_POST["archivoAListar"]);
    if ($_POST["operacion"] == 1)
        $sistemaOperativo->volver($_POST["archivoAVolver"]);
    if ($_POST["operacion"] == 2)
        $sistemaOperativo->crear($_POST["archivoPadre"], $_POST["nombreArchivo"], $_POST["tipoArchivo"]);
    if ($_POST["operacion"] == 3)
        $sistemaOperativo->eliminar($_POST["idArchivoAEliminar"]);
    if ($_POST["operacion"] == 4)
        $sistemaOperativo->entregarCarpetas();
    if ($_POST["operacion"] == 5)
        $sistemaOperativo->mover($_POST["idArchivoAMover"], $_POST["idNuevoPadre"]);
    if ($_POST["operacion"] == 6)
        $sistemaOperativo->renombrar($_POST["idArchivoARenombrar"], $_POST["nuevoNombre"]);     


?> 