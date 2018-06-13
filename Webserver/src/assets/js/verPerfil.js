$(document).ready(function(){                            
    
});

function cambiarEstado(idPersona){
    $.ajax({
        type: "POST",
        url: "callAPI.php?opc=aceptar",
        dataType: "html",
        data: { 
            idPersona: idPersona
        },
        sucess:function(data){
            alert("Permiso concedido a la casa.");
        }
    });
}

function rechazar(idPersona){
    $.ajax({
        type: "POST",
        url: "callAPI.php?opc=cancelar",
        dataType: "html",
        data: { 
            idPersona: idPersona
        },
        sucess:function(data){
            alert("No puede ingresar a la casa.");
        }
    });
}