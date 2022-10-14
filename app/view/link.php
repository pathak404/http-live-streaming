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
    <title>Generate Link</title>

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

    <form id="linkGenerateForm" onsubmit="return ">

        <select name="dir" onchange="selectOnChange(this)">
            <option value="null" selected>Choose Course Dir</option>
            <?php
            foreach (scandir(ROOT_DIR."/.data/") as $single) :
                if($single == "." || $single == "..") continue;
                if(is_dir(ROOT_DIR."/.data/".$single)):
            ?>
                <option value="<?php echo $single; ?>"><?php echo $single; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>


        <select name="topic" onchange="selectOnChange(this)" disabled>
            <option value="null" selected>Choose Topic Dir</option>
        </select>

        <select name="lesson" onchange="selectOnChange(this)" disabled>
            <option value="null" selected>Choose Lesson Dir</option>
        </select>

        <select name="videofile" onchange="selectOnChange(this)" disabled>
            <option value="null" selected>Choose Video file</option>
        </select>

        <button type="submit" style="display: none;"></button>

        <p id="copyText">Copy Data</p>
        <p id="response"></p>

    </form>










    <script>
        var formEle = document.forms.linkGenerateForm;

        var data = ""; // form data var
        var copyText = document.getElementById("copyText");

        var pEle = document.getElementById("response");

        formEle.addEventListener("submit", function(e) {
            e.preventDefault();

            ajax = new XMLHttpRequest();
            ajax.open("POST", "./handle");

            // on progress
            ajax.onprogress = function() {
                pEle.innerText = "Please Wait ...";
            }

            // on load    
            ajax.onload = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    let $res = JSON.parse(ajax.responseText);

                    if ($res.target != 0) {
                        const select = document.querySelectorAll('#linkGenerateForm select')[$res.target];
                        $res.options.forEach(element => {
                            let newOption = new Option(element, element);
                            select.appendChild(newOption);
                        });
                        select.disabled = false;
                        pEle.innerHTML = "";
                    } else {
                        copyText.style.display = "block";
                        pEle.innerText = $res.message;
                    }

                }
            }

            ajax.send(data);

        });

        function selectOnChange(ele) {
                    
            copyText.removeAttribute("style");

            let select1 = document.querySelectorAll("#linkGenerateForm select")[1];
            let select2 = document.querySelectorAll("#linkGenerateForm select")[2];
            let select3 = document.querySelectorAll("#linkGenerateForm select")[3];


            data = new FormData(formEle);
            data.append("action", "link");


            if (ele.getAttribute("name") == "dir") {
                select1.disabled = true;
                select2.disabled = true;
                select3.disabled = true;
                removeOptions(select1);
                removeOptions(select2);
                removeOptions(select3);

                data.append("topic", "null");
                data.append("lesson", "null");
                data.append("videofile", "null");

            }else if (ele.getAttribute("name") == "topic") {
                select2.disabled = true;
                select3.disabled = true;
                removeOptions(select2);
                removeOptions(select3);

                data.append("lesson", "null");
                data.append("videofile", "null");
            }else if (ele.getAttribute("name") == "lesson") {
                select3.disabled = true;
                removeOptions(select3);

                data.append("videofile", "null");
            }

            if (ele.value != "null") {
                document.querySelector("button[type='submit']").click();
            }

        }

        function removeOptions(selectElement) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 1; i--) {
                selectElement.remove(i);
            }
        }


        copyText.addEventListener("click", function() {
            copyToClipboard();
            copyText.innerText = "Copied!";
            setInterval(() => {
                copyText.innerText = "Copy Data";
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