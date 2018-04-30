// Put event listeners into place
window.addEventListener("DOMContentLoaded", function() {
    // Grab elements, create settings, etc.
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
        var dataUrl = canvas.toDataURL("image/jpeg", 0.85);
        console.log(dataUrl);
        $("#uploading").show();
        $.ajax({
          type: "POST",
          url: "html5-webcam-save.php",
          data: { 
             imgBase64: dataUrl,
             user: "Joe",       
             userid: 25         
          }
        }).done(function(msg) {
          console.log("saved");
          $("#uploading").hide();
          $("#uploaded").show();
        });
    });
}, false);