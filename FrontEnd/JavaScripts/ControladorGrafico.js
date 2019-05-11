window.addEventListener("load",load,false);

function load()
{
    // Despliega el menu con las opciones para crear una carpeta, cuando se presione el boton de crear.
    document.getElementById("crear").addEventListener("click", mostrarMenuCreacion, false);
    document.getElementById("cancelarCrear").addEventListener("click", ocultarMenuCreacion, false);

    //Despliega el menu con las opciones para mover una carpeta.
    document.getElementById("mover").addEventListener("click", generarMenuMover, false);
    document.getElementById("cancelarMover").addEventListener("click", ocultarMenuMover, false);

    //Despliega el menu con las opciones para renombrar un archivo.
    document.getElementById("renombrar").addEventListener("click", mostrarMenuRenombrar, false);
    document.getElementById("cancelarRenombrar").addEventListener("click", ocultarMenuRenombrar, false);
}

function mostrarMenuCreacion()
{
    document.getElementById("formCreacion").style = "display:block";
}

function ocultarMenuCreacion()
{
    document.getElementById("formCreacion").style = "display:none";
}

function mostrarMenuRenombrar()
{
    document.getElementById("formRenombrar").style = "display:block";
}

function ocultarMenuRenombrar()
{
    document.getElementById("formRenombrar").style = "display:none";
}

function mostrarMenuMover()
{
    document.getElementById("formMover").style = "display:block";
}

function ocultarMenuMover()
{
    document.getElementById("formMover").style = "display:none";
}

function generarMenuMover()
{
    //Le pide a la base de datos todas las carpetas para armar el formulario con ellas
    $.post("../../Backend/Clases/SistemaOperativo.php", 
            {operacion: 4}, 
            function(respuesta)
            {
                //alert(respuesta);
                var carpetas = JSON.parse(respuesta); //Captura el arreglo con todas las carpetas
                var html = "<option value='2'>Disco Local C</option>"; //Inicia con la opcion del disco duro, que siempre estara
                for(let i=0; i<carpetas.length; i++)
                {
                    html += "<option value='"+carpetas[i].archivoID+"'>"+carpetas[i].nombre+"</option>";
                }
                document.getElementById("carpetaAMover").innerHTML = html;

            });
    mostrarMenuMover();
}

