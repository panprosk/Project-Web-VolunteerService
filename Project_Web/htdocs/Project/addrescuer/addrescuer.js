function addrescuerSubmit(){
    document.querySelector("#failedaddrescuer").textContent="";
    var username = document.querySelector("#username").value;
    var pwd = document.querySelector("#pwd").value;
    fetch("addrescuer.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("username="+username+"&pswd="+pwd)
    }).then(response => response.text())
      .then(text => {
        if(text !== "Success"){
            document.querySelector("#failedaddrescuer").classList.remove("text-success");
            document.querySelector("#failedaddrescuer").classList.add("text-danger");
            document.querySelector("#failedaddrescuer").textContent="This username has already been taken";
        }
        else{
            document.querySelector("#failedaddrescuer").classList.add("text-success");
            document.querySelector("#failedaddrescuer").classList.remove("text-danger");
            document.querySelector("#failedaddrescuer").textContent="Success";
            document.querySelector("form").reset();
        }
    });
}