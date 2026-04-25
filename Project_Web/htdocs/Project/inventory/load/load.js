const qstatus = document.querySelector("#status");

function urldecoder(){
    qstatus.textContent = "";
    var url = document.querySelector("#url").value;
    fetch("urldecoder.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("url="+url)
    }).then(response => response.text())
      .then(text => {
        if(text === "Success!"){
            qstatus.classList.add("text-success");
            qstatus.classList.remove("text-danger");
            document.querySelector("#urlform").reset();
        }
        else{
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success")
        }
        qstatus.textContent = text;
    });
}

function filedecoder(){
    qstatus.textContent = "";
    var myfile = document.querySelector("#file").files[0];
    var myform = new FormData();
    myform.append('file', myfile);

    fetch("filedecoder.php", {
        method: "POST",
        body: myform
    }).then(response => response.text())
      .then(text => {
        if(text === "Success!"){
            qstatus.classList.add("text-success");
            qstatus.classList.remove("text-danger");
            document.querySelector("#fileform").reset();
        }
        else{
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success");
        }
        qstatus.textContent = text;
    });
}