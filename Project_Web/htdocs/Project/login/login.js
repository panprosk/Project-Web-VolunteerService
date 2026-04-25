function loginSubmit(){
    document.querySelector("#failedlogin").textContent="";
    var username = document.querySelector("#username").value;
    var pwd = document.querySelector("#pwd").value;
    fetch("login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("username="+username+"&pswd="+pwd)
    }).then(response => response.text())
      .then(text => {
        if(text !== "Success"){
            document.querySelector("#failedlogin").textContent="Wrong username or password";
        }
        else location.href = "../home";
    });
}

document.querySelector("#btn-login").onclick=loginSubmit;