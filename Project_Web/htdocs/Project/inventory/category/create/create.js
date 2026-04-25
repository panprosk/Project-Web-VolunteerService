const qstatus = document.querySelector("#status");

function create(){
    qstatus.textContent = "";
    var name = document.querySelector("#name").value;
    fetch("create.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("name="+name)
    }).then(response => response.text())
      .then(text => {
        if(text === "Success!"){
            qstatus.classList.add("text-success");
            qstatus.classList.remove("text-danger");
            document.querySelector("form").reset();
        }
        else{
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success");
        }
        qstatus.textContent = text;
    });
}