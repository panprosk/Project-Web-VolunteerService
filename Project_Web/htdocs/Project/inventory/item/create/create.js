const qstatus = document.querySelector("#status");

var detailcount = 1;
const template = document.querySelector("#detail1").cloneNode(true);

function create(){
    qstatus.textContent = "";
    var category = document.querySelector("#id").value;
    var name = document.querySelector("#name").value;
    var data = '{"name":"'+name+'","category":"'+category+'","details":[';
    for(let i = 1; i <= detailcount; i++){
        var detailname = document.querySelector("#detailname"+i).value;
        var detailvalue = document.querySelector("#detailvalue"+i).value;
        data += '{"detail_name":"'+detailname+'","detail_value":"'+detailvalue+'"},'
    }
    data = data.slice(0,-1);
    data += "]}";
    fetch("create.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "data="+data
    }).then(response => response.text())
      .then(text => {
        if(text === "Success!"){
            qstatus.classList.add("text-success");
            qstatus.classList.remove("text-danger");
            document.querySelector("#createform").reset();
            document.querySelector("#details").innerHTML='';
            document.querySelector("#details").append(template.cloneNode(true));
            detailcount = 1;
        }
        else{
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success");
        }
        qstatus.textContent = text;
    });
}

function newDetail(){
    detailcount = detailcount + 1;
    var newnode = template.cloneNode(true);
    newnode.id = "detail" + detailcount;
    newnode.children[0].children[0].id = "detailname" + detailcount;
    newnode.children[0].children[0].name = "detailname" + detailcount;
    newnode.children[1].children[0].id = "detailvalue" + detailcount;
    newnode.children[1].children[0].name = "detailvalue" + detailcount;
    document.querySelector("#details").append(document.createElement("br"));
    document.querySelector("#details").append(newnode);
}