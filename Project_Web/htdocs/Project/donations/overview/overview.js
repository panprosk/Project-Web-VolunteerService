const qstatus = document.querySelector("#status");

const tablebody = document.querySelector("#tablebody");

var source;

function cancelDonation(id){
    fetch("cancel.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("id="+id)
    }).then(response => response.text())
        .then(text => {
            if (text !== "Donation not found!") {
                location.reload();
            }
            else {
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
                qstatus.textContent = text;
            }
        });
}

function fillTable(){
    for(let record of source.records){
        let newrowelement = document.createElement("tr")
        let newbodyelement;
        for(let property in record){
            newbodyelement = document.createElement("td");
            if(record[property] === null)
                newbodyelement.textContent = "---";
            else
                newbodyelement.textContent = record[property];
            newrowelement.append(newbodyelement);
        }
        newbodyelement = document.createElement("td");
        if(record[1] === "Completed")
            newbodyelement.innerHTML = '<a class="btn btn-dark disabled" href="javascript: cancelDonation(' + record[0] + ')"> Cancel</a>';
        else
            newbodyelement.innerHTML = '<a class="btn btn-dark" href="javascript: cancelDonation(' + record[0] + ')"> Cancel</a>';
        newrowelement.append(newbodyelement);
        tablebody.append(newrowelement);
    }
}

fetch("overview.php", {
    method: "POST",
}).then(response => response.text())
    .then(text => {
        if (text !== "There are no donations!") {
            console.log(text);
            source = JSON.parse(text);
            fillTable();
        }
        else {
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success");
            qstatus.textContent = text;
        }
    });