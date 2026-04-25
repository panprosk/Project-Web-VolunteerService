const qstatus = document.querySelector("#status");

const tablebody = document.querySelector("#tablebody");

var source;

function fillTable(){
    for(let record of source.records){
        newrowelement = document.createElement("tr")
        for(let property in record){
            newbodyelement = document.createElement("td");
            if(record[property] === null)
                newbodyelement.textContent = "---";
            else
                newbodyelement.textContent = record[property];
            newrowelement.append(newbodyelement);
        }
        tablebody.append(newrowelement);
    }
}

fetch("overview.php", {
    method: "POST",
}).then(response => response.text())
    .then(text => {
        if (text !== "There are no requests!") {
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