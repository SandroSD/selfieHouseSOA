<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>selfieHouse</title>
    <link rel="icon" type="image/svg" href="src/assets/plugins/camera.svg"> 

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <!--script src="Webserver/src/assets/js/jquery.min.js"></script-->
    

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    
    <script src="src/assets/js/index.js"></script>
    <link rel="stylesheet" href="src/assets/css/index.css">
</head>    

<body>
<nav class="navbar navbar-dark bg-dark">
    <span class="navbar-brand mb-0 h1"><i class="fas fa-home"></i> &nbsp;Ingreso - selfieHouse</span>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-5 col-lg-5 offset-md-3 offset-lg-3">
            <div class="camcontent">
                <video id="video" autoplay></video>
                <canvas id="canvas" width="640" height="480"></canvas>
            </div>
            <div class="cambuttons">
                <button class="btn btn-info" id="snap">Capturar Foto</button> 
                <button class="btn btn-warning" id="reset">Reiniciar</button>     
                <button class="btn btn-success" id="upload">Subir</button> 
                <div>
                    <!--span id=uploading> Uploading has begun . . .  </span> 
                    <span id=uploaded> Success, your photo has been uploaded! 
                    <a href="javascript:history.go(-1)"> Return </a> </span-->
                </div>
            </div> 
        </div>           
    </div>
</div>

</body>
</html>