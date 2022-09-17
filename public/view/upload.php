<?php 
//* Don't access this file directly
defined('ABSPATH') or die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>

    <style>
        #copyText {
            cursor: pointer;
            padding: 5px;
            display: none;
            width: fit-content;
        }
    </style>
</head>
<body>

    <form id="uploadForm" enctype="multipart/form-data">
        <input type="text" name="dir" placeholder="Course Name" required>
        <input type="text" name="topic" placeholder="Topic Name" required>
        <input type="text" name="lesson" placeholder="Lesson Name" required>
        <input type="file" name="videofile" accept="video/*" required>
        <input type="submit" name="submit" value="submit">
        <p id="percentage"></p>
        <p id="copyText">Copy Link</p>
        <p id="response"></p>
    </form>


    <script>
        var formEle = document.forms.uploadForm;
        var pEle = document.getElementById("response");
        var copyText = document.getElementById("copyText");

        formEle.addEventListener("submit", function(e){
            e.preventDefault();

            pEle.innerText = "";
            copyText.removeAttribute("style");

            let data = new FormData(formEle);
            data.append("action", "save");

            ajax = new XMLHttpRequest();
            ajax.open("POST", "./handle");

            // on load    
            ajax.onload = function(){
                if(ajax.readyState == 4 && ajax.status == 200){
                    console.log(ajax.responseText);
                    let $res = JSON.parse(ajax.responseText);
                    copyText.style.display = "block";
                    pEle.innerText = $res.message;
                    document.getElementById("percentage").innerText = "";
                }
            }

            // on progress
            ajax.upload.addEventListener("progress", function(e){
                let percentage = (e.loaded / e.total) * 100;
                document.getElementById("percentage").innerText = Math.round(percentage) + " %";
            }, false);


            ajax.send(data);
            
        });


        copyText.addEventListener("click", function() {
            copyToClipboard();
            copyText.innerText = "Copied!";
            setInterval(() => {
                copyText.innerText = "Copy Link";
            }, 3000);
        });


        function copyToClipboard() {
            var range = document.createRange();
            range.selectNode(pEle);
            window.getSelection().removeAllRanges(); // clear current selection
            window.getSelection().addRange(range); // to select text
            document.execCommand("copy");
            window.getSelection().removeAllRanges(); // to deselect
        }
        
        
    </script>
    
</body>
</html>