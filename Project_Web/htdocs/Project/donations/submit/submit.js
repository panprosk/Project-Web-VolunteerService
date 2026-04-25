const qstatus = document.querySelector("#status");

var source;

function selectCategory() {
    const selcat = document.querySelector("#selcatid");
    let options;
    document.querySelectorAll(".selection").forEach(option => option.setAttribute("hidden", true))
    if(selcat.value > 0){
        options = document.querySelectorAll(".category" + selcat.value);
        options.forEach(option => option.removeAttribute("hidden"));
    }
}

function submitSelect() {
    let id = document.querySelector("#selitid").value;
    let quantity = document.querySelector("#selquantity").value;
    postDonation(id, quantity);
}

function postDonation(id, quantity) {
    fetch("submit.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "id=" + id + "&quantity=" + quantity
    }).then(response => response.text())
        .then(text => {
            if (text === "Success!") {
                qstatus.classList.add("text-success");
                qstatus.classList.remove("text-danger");
                qstatus.textContent = text;
                document.querySelectorAll("form").forEach(myform => myform.reset());
            }
            else {
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
                qstatus.textContent = text;
            }
        });
}