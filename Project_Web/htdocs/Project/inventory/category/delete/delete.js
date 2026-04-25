const qstatus = document.querySelector("#status");

function cdelete(){
    qstatus.textContent = "";
    var id = document.querySelector("#id").value;
    fetch("delete.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("id="+id)
    }).then(response => response.text())
      .then(text => {
        if(text === "Success!"){
            qstatus.classList.add("text-success");
            qstatus.classList.remove("text-danger");
        }
        else{
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success");
        }
        qstatus.textContent = text;
    });
}