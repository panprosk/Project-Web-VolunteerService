const qstatus = document.querySelector("#status");

const tablebody = document.querySelector("#tablebody");

const enable = document.querySelector("#en");

const disable = document.querySelector("#ds");

const spans = new Object();

function gcd(a, b) {
    let dividend = a;
    let divisor = b;
    while (dividend % divisor !== 0) {
        console.log(dividend + " " + divisor);
        let temp = divisor;
        divisor = dividend % divisor;
        dividend = temp;
        console.log(dividend + " " + divisor);
    }
    return divisor;
}

function enableCategory(){
    var id = enable.value;
    if(id === "0") return;

    affected = document.querySelectorAll(".categoryhide" + id);

    affected.forEach((rows) => {
        rows.removeAttribute("hidden");
    });

    document.querySelector("#en" + id).setAttribute("hidden", true);
    document.querySelector("#ds" + id).removeAttribute("hidden");
    enable.selectedIndex = 0;
}

function disableCategory(){
    var id = disable.value;
    if(id === "0") return;

    console.log("#categoryhide" + id);
    affected = document.querySelectorAll(".categoryhide" + id);

    affected.forEach((rows) => {
        rows.setAttribute("hidden", true);
    });

    document.querySelector("#ds" + id).setAttribute("hidden", true);
    document.querySelector("#en" + id).removeAttribute("hidden");
    disable.selectedIndex = 0;
}

function increase(id){
    let basequantity = document.querySelector("#basequantity" + id);
    fetch("manage.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=2&id=" + id
    }).then(response => response.text())
    .then(text => {
        if(text !== "Fail"){
            let num = Number(basequantity.textContent);
            basequantity.textContent = ++num;
        }
    });
}

function decrease(id){
    let basequantity = document.querySelector("#basequantity" + id);
    fetch("manage.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=3&id=" + id
    }).then(response => response.text())
    .then(text => {
        if(text !== "Fail"){
            let num = Number(basequantity.textContent);
            basequantity.textContent = --num;
        }
    });
}

// Fetch items, categories, details and inventory
var source;
fetch("manage.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=1"
}).then(response => response.text())
    .then(text => {
        if (text !== "There are no items!") {
            console.log(text);
            source = JSON.parse(text);
            for (category of source.categories) {
                category.disabled = "yes";
                console.log(JSON.stringify(category));
            }
            // Initialize table body

            for (let category of source.categories) {
                spans["categoryname" + category['ca_id']] = category['ca_name'];
                spans["categorycount" + category['ca_id']] = 0;
                spans["categoryelement" + category['ca_id']] = document.createElement("div");
                spans["categoryelement" + category['ca_id']].id = "category" + category['ca_id'];
            }

            for (let item of source.items) {
                spans["itemname" + item['it_id']] = item['it_name'];
                spans["itemcategory" + item['it_id']] = item['it_ca_id'];
                spans["itemdetailcount" + item['it_id']] = 0;
                spans["itemcontainercount" + item['it_id']] = 0;
                spans["itemelement" + item['it_id']] = document.createElement("div");
                spans["itemelement" + item['it_id']].id = "item" + item['it_id'];
            }

            for (let detail of source.details) {
                if (detail['de_name'] === "") detail['de_name'] = "null";
                if (detail['de_value'] === "") detail['de_value'] = "null";
                spans["itemdetailcount" + detail['de_it_id']]++;
            }

            for (let container of source.inventory) {
                if (container['in_ve_id'] === null) container['in_ve_id'] = "Base";
                spans["itemcontainercount" + container['in_it_id']]++;
            }

            console.log("hello1");

            for (let item of source.items) {
                let a = spans["itemdetailcount" + item['it_id']];
                let b = spans["itemcontainercount" + item['it_id']];
                spans["itemcount" + item['it_id']] = a * b / gcd(a, b);
                if (a < b)
                    spans["itemrows" + item['it_id']] = spans["itemcount" + item['it_id']] / a;
                else
                    spans["itemrows" + item['it_id']] = spans["itemcount" + item['it_id']] / b;
                spans["itemdetailcount" + item['it_id']] = spans["itemcount" + item['it_id']] / a;
                spans["itemcontainercount" + item['it_id']] = spans["itemcount" + item['it_id']] / b;
                spans["categorycount" + item['it_ca_id']] += spans["itemcount" + item['it_id']];
                spans["itemdetailindex" + item['it_id']] = 0;
                spans["itemcontainerindex" + item['it_id']] = 0;
                for (let i = 0; i < spans["itemcount" + item['it_id']]; i++) {
                    let newelement = document.createElement("tr");
                    newelement.classList.add("categoryhide" + item['it_ca_id']);
                    newelement.setAttribute("hidden", true);
                    //newelement.id = "item" + item['it_id'] + "row" + i;
                    if (i === 0) {
                        let newitem = document.createElement("td");
                        newitem.textContent = item['it_name'] + " (" + item['it_id'] + ")";
                        newitem.setAttribute("rowspan", spans["itemcount" + item['it_id']] = a * b / gcd(a, b));
                        newelement.append(newitem);
                    }
                    spans["itemelement" + item['it_id']].append(newelement);
                    spans["item" + item['it_id'] + "row" + i] = newelement;
                    console.log("item" + item['it_id'] + "row" + i);
                }
            }

            console.log("hello2");

            for (let detail of source.details) {
                let dname = document.createElement("td");
                let dvalue = document.createElement("td");
                let rownum = spans["itemdetailcount" + detail['de_it_id']];
                console.log("item" + detail['de_it_id'] + "row" + spans["itemdetailindex" + detail['de_it_id']]);
                let row = spans["item" + detail['de_it_id'] + "row" + spans["itemdetailindex" + detail['de_it_id']]];
                dname.textContent = detail['de_name'];
                dname.setAttribute("rowspan", rownum);;
                dvalue.textContent = detail['de_value'];
                dvalue.setAttribute("rowspan", rownum);;
                row.append(dname);
                row.append(dvalue);
                spans["itemdetailindex" + detail['de_it_id']] += rownum;
            }

            for (let container of source.inventory) {
                if (container['in_ve_id'] !== "Base") {
                    let cname = document.createElement("td");
                    let cquantity = document.createElement("td");
                    let rownum = spans["itemcontainercount" + container['in_it_id']];
                    let row = spans["item" + container['in_it_id'] + "row" + spans["itemcontainerindex" + container['in_it_id']]];
                    cname.textContent = container['in_ve_id'];
                    cname.setAttribute("rowspan", rownum);
                    cquantity.textContent = container['in_quantity'];
                    cquantity.setAttribute("rowspan", rownum);
                    row.append(cname);
                    row.append(cquantity);
                    spans["itemcontainerindex" + container['in_it_id']] += rownum;
                }
            }

            for (let container of source.inventory) {
                if (container['in_ve_id'] === "Base") {
                    let cname = document.createElement("td");
                    let cquantity = document.createElement("td");
                    let rownum = spans["itemcontainercount" + container['in_it_id']];
                    let row = spans["item" + container['in_it_id'] + "row" + spans["itemcontainerindex" + container['in_it_id']]];
                    cname.textContent = container['in_ve_id'];
                    cname.setAttribute("rowspan", rownum);;

                    quan = document.createElement("span");
                    quan.id = "basequantity" + container['in_it_id'];
                    quan.textContent = container['in_quantity'];
                    let inc = document.createElement("a");
                    inc.href="javascript: increase('" + container['in_it_id'] + "')";
                    inc.textContent = "+"
                    inc.style.textDecoration = "none";
                    let dec = document.createElement("a");
                    dec.href="javascript: decrease('" + container['in_it_id'] + "')";
                    dec.textContent = "-"
                    dec.style.textDecoration = "none";
                    cquantity.append(quan);
                    cquantity.append(" ");
                    cquantity.append(inc);
                    cquantity.append("/");
                    cquantity.append(dec);
                    cquantity.setAttribute("rowspan", rownum);
                    row.append(cname);
                    row.append(cquantity);
                    console.log(cquantity.textContent);
                    spans["itemcontainerindex" + container['in_it_id']] += rownum;
                }
            }

            for (let category of source.categories) {
                if (spans["categorycount" + category['ca_id']] > 0) {
                    let newcategory = document.createElement("td");
                    newcategory.setAttribute("rowspan", spans["categorycount" + category['ca_id']] + 1);
                    newcategory.textContent = category['ca_name'] + " (" + category['ca_id'] + ")";
                    let newrow = document.createElement("tr");
                    newrow.classList.add("categoryhide" + category['ca_id']);
                    newrow.setAttribute("hidden", true);
                    newrow.append(newcategory);
                    spans["categoryelement" + category['ca_id']].append(newrow);
                    tablebody.append(spans["categoryelement" + category['ca_id']]);
                }
            }

            for (let item of source.items) {
                spans["categoryelement" + item['it_ca_id']].append(spans["itemelement" + item['it_id']]);
            }

            for (let item of source.items) {
                nodeToBeRemoved = document.querySelector("#item" + item['it_id']);

                while (nodeToBeRemoved.firstChild) {
                    nodeToBeRemoved.parentNode.insertBefore(nodeToBeRemoved.firstChild, nodeToBeRemoved);
                }

                nodeToBeRemoved.parentNode.removeChild(nodeToBeRemoved);
            }

            for (let category of source.categories) {
                if (spans["categorycount" + category['ca_id']] > 0) {
                    nodeToBeRemoved = document.querySelector("#category" + category['ca_id']);

                    while (nodeToBeRemoved.firstChild) {
                        nodeToBeRemoved.parentNode.insertBefore(nodeToBeRemoved.firstChild, nodeToBeRemoved);
                    }

                    nodeToBeRemoved.parentNode.removeChild(nodeToBeRemoved);
                }
            }
        }
        else {
            qstatus.classList.add("text-danger");
            qstatus.classList.remove("text-success");
            qstatus.textContent = text;
        }
    });