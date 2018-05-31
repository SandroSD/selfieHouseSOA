// Put event listeners into place
window.addEventListener("DOMContentLoaded", function() {
    // Grab elements, create settings, etc.
    cargarSeccion();

       

}, false);

function registrarCodigo(){
    $(".ingresoCodigo").click(function(){
        var scopeBtn = $(this);
        var valor = scopeBtn.data("val");
        var inputCodigo = $("input[name=codigo]");
        var inputHidden = $("input[name=guardarCodigo]");

        var resultado = "";
        var valorAGuardar = "";
        
        console.log("Contenido del hidden antes del switch: "+inputHidden.val());
        switch (valor) {
            case 0:
            case 1:
            case 2:    
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                console.log("Valor: "+valor);
                //console.log("Contenido del input: "+inputCodigo);                
                valorAGuardar+=String(valor);
                inputHidden.val(valorAGuardar);
                console.log("Contenido del hidden en el switch: "+inputHidden.val());
                inputHidden.val(valorAGuardar);
                console.log("----------------------------------");
                //console.log("Valor a guardar: "+valorAGuardar);

            break;
        
            
        }

    });
}

function cargarSeccion(){
    //$(".container-fluid .row").load("src/assets/templates/camara.php");
    $.ajax({
        type: "POST",
        url: "src/assets/templates/camara.php",
        data: { 
           
        },
        dataType: "html",
        success: function(data){
            funcionDeCamara();                        
        }
      }).done(function(msg) {
        
      });
    //funcionDeCamara();
}

function funcionDeCamara(){
    var canvas = document.getElementById("canvas"),
        context = canvas.getContext("2d"),
        video = document.getElementById("video"),
        videoObj = { "video": true },
        image_format= "jpeg",
        jpeg_quality= 85,
        errBack = function(error) {
            console.log("Video capture error: ", error.code); 
        };
        

    // Put video listeners into place
    if(navigator.getUserMedia) { // Standard
        navigator.getUserMedia(videoObj, function(stream) {
            video.src = stream;
            video.play();
            $("#snap").show();
        }, errBack);
    } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
        navigator.webkitGetUserMedia(videoObj, function(stream){
            video.src = window.webkitURL.createObjectURL(stream);
            video.play();
            $("#snap").show();
        }, errBack);
    } else if(navigator.mozGetUserMedia) { // moz-prefixed
        navigator.mozGetUserMedia(videoObj, function(stream){
            video.src = window.URL.createObjectURL(stream);
            video.play();
            $("#snap").show();
        }, errBack);
    }
          // video.play();       these 2 lines must be repeated above 3 times
          // $("#snap").show();  rather than here once, to keep "capture" hidden
          //                     until after the webcam has been activated.  

    // Get-Save Snapshot - image 
    document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 640, 480);
        // the fade only works on firefox?
        //$("#video").fadeOut("fast");
        $("#video").hide();
        $("#canvas").fadeIn("slow");
        $("#snap").hide();
        $("#reset").show();
        $("#upload").show();
    });
    // reset - clear - to Capture New Photo
    document.getElementById("reset").addEventListener("click", function() {
        $("#video").fadeIn("slow");
        $("#canvas").fadeOut("slow");
        $("#snap").show();
        $("#reset").hide();
        $("#upload").hide();
    });
    // Upload image to sever 
    document.getElementById("upload").addEventListener("click", function(){
        var url = canvas.toDataURL("image/jpeg", 0.85);        
        $("#uploading").show();
        $.ajax({
          type: "POST",
          url: "/src/plugins/html5-webcam-save.php",
          data: { 
             url: url                    
          },
          sucess:function(data){
            
          }
        }).done(function(msg) {          
          $("#uploading").hide();
          $("#uploaded").show();          
        });
    });
}
