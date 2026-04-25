const qstatus = document.querySelector("#status");

const tablebody = document.querySelector("#tablebody");

const enable = document.querySelector("#en");

const disable = document.querySelector("#ds");

function enableItem() {
    var id = enable.value;
    if (id === "0") return;

    document.querySelector("#en" + id).setAttribute("hidden", true);
    document.querySelector("#ds" + id).removeAttribute("hidden");
    enable.selectedIndex = 0;
}

function disableItem() {
    var id = disable.value;
    if (id === "0") return;

    document.querySelector("#ds" + id).setAttribute("hidden", true);
    document.querySelector("#en" + id).removeAttribute("hidden");
    disable.selectedIndex = 0;
}

function reset() {
    disable.querySelectorAll('option').forEach((option) => {
        option.setAttribute("hidden", true);
    });
    enable.querySelectorAll('option').forEach((option) => {
        option.removeAttribute("hidden");
    });
}

function submitAnnouncement() {
    let ids = enable.querySelectorAll("[hidden]");
    let data = '{"ids":['
    ids.forEach((id) => {
        data += id.value + ",";
    });
    data = data.slice(0, -1);
    data += "]}";
    console.log(data);
    fetch("announcements.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "data=" + data
    }).then(response => response.text())
        .then(text => {
            if (text === "Success!") {
                qstatus.classList.add("text-success");
                qstatus.classList.remove("text-danger");
                reset();
            }
            else {
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
            }
            qstatus.textContent = text;
        });
}