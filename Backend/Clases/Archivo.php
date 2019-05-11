<?php
    class Archivo
    {
        public $archivoID;
        public $nombre;
        public $direccionImagen;
        public $extension;
        public $padre;

        function __construct($archivoID, $nombre, $tipo, $padre)
        {
            $this->archivoID = $archivoID;
            $this->nombre = $nombre;
            $this->asignarDireccionImagen($tipo);
            $this->padre = $padre;
        }

        function asignarDireccionImagen($tipo)
        {
            if(strcmp($tipo, "Imagen") == 0)
            {
                $this->direccionImagen = "../../Imagenes/Imagen.png";
                $this->extension = ".jpg";
            }
            if(strcmp($tipo, "Disco local") == 0)
            {
                $this->direccionImagen = "../../Imagenes/DiscoDuro.png";
                $this->extension = "";
            }
            if(strcmp($tipo, "Carpeta") == 0)
            {
                $this->direccionImagen = "../../Imagenes/Carpeta.png";
                $this->extension = "";
            }
            if(strcmp($tipo, "HojaCalculo") == 0)
            {
                $this->direccionImagen = "../../Imagenes/HojaCalculo.png";
                $this->extension = ".xlx";
            }
            if(strcmp($tipo, "Documento") == 0)
            {
                $this->direccionImagen = "../../Imagenes/Texto.png";
                $this->extension = ".txt";
            }
        }

    }

?> 