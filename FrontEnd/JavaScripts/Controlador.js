window.addEventListener("load",load,false);

function load()
{
    listar("null"); //Primera vez, para listar C.
}

function listar(idArchivoAListar)
{
    $.post("../../Backend/Clases/SistemaOperativo.php", 
            {archivoAListar: idArchivoAListar, operacion: 0}, 
            function(respuesta)
            {
                //alert(respuesta);//Documentarlo al presentarlo
                var archivosHijos = JSON.parse(respuesta);
                var html = "";
                if(idArchivoAListar != "null")
                {
                    document.getElementById("atras").style = "display:block";
                    document.getElementById("crear").style = "display:block";
                    document.getElementById("eliminar").style = "display:block";
                    document.getElementById("mover").style = "display:block";
                    document.getElementById("renombrar").style = "display:block";
                    document.getElementById("atras").onclick = function(){
                        volver(idArchivoAListar);
                        document.getElementById("formMover").style = "display:none";
                        document.getElementById("formCreacion").style = "display:none";
                        document.getElementById("formRenombrar").style = "display:none";
                    };
                    document.getElementById("enviarDatos").onclick = function(){crear(idArchivoAListar, idArchivoAListar)};
                    document.getElementById("eliminar").onclick = function(){eliminar(idArchivoAListar)};
                    document.getElementById("moverArchivo").onclick = function(){mover(idArchivoAListar)};
                    document.getElementById("renombrarArchivo").onclick = function(){renombrar(idArchivoAListar)};
                }
                else
                {
                    document.getElementById("atras").style = "display:none";
                    document.getElementById("crear").style = "display:none";
                    document.getElementById("eliminar").style = "display:none";
                    document.getElementById("mover").style = "display:none";
                    document.getElementById("renombrar").style = "display:none";
                }
                html += "<div class = 'row'>";
                if(archivosHijos.length == 0)
                    html += "<p>La carpeta esta vacia</p>"
                else
                    for (let i=0; i<archivosHijos.length; i++) //Pone cada archivo en el HTML, su nombre, icono y extension.
                    {
                        html += "<div class ='col-1 archivo'>";
                        html += "<div class = 'row justify-content-center align-items-center'>";
                        if(archivosHijos[i].extension == "") //Verifica que sea una carpeta o un disco local, para que se pueda ingresar a ella
                        {   
                            html += "<img onclick='listar("+archivosHijos[i].archivoID+")' class='col-12' src='"+archivosHijos[i].direccionImagen+"'></img>";
                            html += "<p class='col-12 nombre' onclick='listar("+archivosHijos[i].archivoID+")'>"+archivosHijos[i].nombre+""+archivosHijos[i].extension+"</p><input type='radio' class='checkArchivos' name='archivos' id='"+archivosHijos[i].archivoID+"'></div></div>";
                        }
                        else
                        {
                            html += "<img class='col-12' src='"+archivosHijos[i].direccionImagen+"'></img>";
                            html += "<p class='col-12 nombre'>"+archivosHijos[i].nombre+""+archivosHijos[i].extension+"</p><input type='radio' class='checkArchivos' name='archivos' id='"+archivosHijos[i].archivoID+"'></div></div>";
                        }
                        
                    }
                html += "</div>";
                document.getElementById("contenedor").innerHTML = html;
            });
}

function volver(idArchivoAVolver)
{   
    $.post("../../Backend/Clases/SistemaOperativo.php", 
            {archivoAVolver: idArchivoAVolver, operacion: 1}, 
            function(respuesta)
            {
                //alert(respuesta);
                var padre = JSON.parse(respuesta);
                if (padre == null)
                    listar("null");
                else
                    listar(padre);
            });   
}

function crear(idPadre, idListar)
{
    var nombreArchivo = document.getElementById("nombreArchivo").value;
    var tipoArchivo = document.getElementById("tipoArchivo").value;
    $.post("../../Backend/Clases/SistemaOperativo.php", 
            {archivoPadre: idPadre, operacion: 2, nombreArchivo: nombreArchivo, tipoArchivo: tipoArchivo}, 
            function(respuesta)
            {
                //alert(respuesta);
                listar(idListar);
            });
    document.getElementById("formCreacion").style = "display:none";
}

function eliminar(idListar)
{
    var archivosSeleccionados = document.getElementsByName("archivos");
    for( i=0; i<archivosSeleccionados.length; i++)
    {
        if(archivosSeleccionados[i].checked)
        {
            idArchivoAEliminar = archivosSeleccionados[i].id
            $.post("../../Backend/Clases/SistemaOperativo.php", 
            {idArchivoAEliminar: idArchivoAEliminar, operacion: 3}, 
            function(respuesta)
            {
               // alert(respuesta);
                listar(idListar);
            });
        }
    }
}

function mover(idListar)
{
    var archivosSeleccionados = document.getElementsByName("archivos");
    for( i=0; i<archivosSeleccionados.length; i++)
    {
        if(archivosSeleccionados[i].checked)
        {
            idArchivoAMover = archivosSeleccionados[i].id
            var idNuevoPadre = document.getElementById("carpetaAMover").value;
            if(idArchivoAMover == idNuevoPadre)
                alert("La carpeta no se puede mover a si misma");
            else
            {
                $.post("../../Backend/Clases/SistemaOperativo.php", 
                {idArchivoAMover: idArchivoAMover, idNuevoPadre: idNuevoPadre, operacion: 5}, 
                function(respuesta)
                {
                    if(respuesta != true)
                        alert(respuesta);
                    listar(idListar);
                });
            }
        }
    }
    document.getElementById("formMover").style = "display:none";
}

function renombrar(idListar)
{
    var nuevoNombre = document.getElementById("nuevoNombreArchivo").value;
    var archivosSeleccionados = document.getElementsByName("archivos");
    for( i=0; i<archivosSeleccionados.length; i++)
    {
        if(archivosSeleccionados[i].checked)
        {
            idArchivoARenombrar = archivosSeleccionados[i].id
            $.post("../../Backend/Clases/SistemaOperativo.php", 
            {idArchivoARenombrar: idArchivoARenombrar, nuevoNombre: nuevoNombre, operacion: 6}, 
            function(respuesta)
            {
                //alert(respuesta);
                listar(idListar);
            });
        }
    }
    document.getElementById("formRenombrar").style = "display:none";
}

